<?php


namespace App\Filter;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Filter
{
    const SESSION_FILTER_KEY = 'filters';

    /**
     * @var array
     */
    private $filterData;

    /**
     * @var RequestStack
     */
    private $rs;

    /**
     * @param RequestStack $rs
     */
    public function __construct(RequestStack $rs)
    {
        $this->rs = $rs;
        $this->filterData = [];
    }

    /**
     * @return null|Request
     */
    public function getRequest()
    {
        if ($this->rs) {
            return $this->rs->getCurrentRequest();
        }

        return null;
    }

    /**
     * @param Request     $request
     * @param array       $filters
     * @param bool|string $sessionKeyParam
     *
     * @throws \InvalidArgumentException
     *
     * @return Filter
     */
    public function getFilter(Request $request, $filters, $sessionKeyParam = false)
    {
        $sessionKey = false;
        if ($sessionKeyParam) {
            if (is_string($sessionKeyParam)) {
                $sessionKey = $sessionKeyParam;
            } else {
                $sessionKey = $this->getRequest()->get('_route');
            }
        }

        $sessionFilter = null;
        if ($sessionKey) {
            $session = $this->getRequest()->getSession();
            $sessionFilters = $session->get(self::SESSION_FILTER_KEY, []);
            $sessionFilter = [];
            if (array_key_exists($sessionKey, $sessionFilters)) {
                $sessionFilter = $sessionFilters[$sessionKey];
            }
            if (!is_array($sessionFilter)) {
                $sessionFilter = [];
            }
        }

        foreach ($filters as $field => $typeData) {
            if (is_string($typeData)) {
                $type = $typeData;
                $options = null;
                $closure = null;
            } else {
                $type = $typeData['type'];
                $options = (array_key_exists('options', $typeData))
                    ? $typeData['options']
                    : null;
                $closure = (array_key_exists('closure', $typeData))
                    ? $typeData['closure']
                    : null;
            }

            $getField = str_replace('.', '_', $field);

            if ($sessionKey) {
                $value = $request->query->get($getField, array_key_exists($field, $sessionFilter) ? $sessionFilter[$field] : null);
                $sessionFilter[$field] = $value;
            } else {

                if ($options !== null) {
                    $value = '';
                    foreach ($options as $option){
                        if ($option['id'] == $request->query->get($getField)) {
                            $value = $request->query->get($getField);
                        }
                    }
                } else {
                    $value = $request->query->get($getField);
                }
            }

            $filterData = [
                'field' => $field,
                'value' => $value,
                'type' => $type,
                'options' => $options,
                'closure' => $closure
            ];

            if (is_array($typeData) && isset($typeData['ignore_chars'])) {
                $filterData['ignore_chars'] = $typeData['ignore_chars'];
            }

            $this->filterData[] = $filterData;
        }


        if ($sessionKey) {
            $sessionFilters[$sessionKey] = $sessionFilter;
            /** @var SessionInterface $session */
            $session->set(self::SESSION_FILTER_KEY, $sessionFilters);
        }

        return $this;
    }

    /**
     *
     * @param QueryBuilder $qb
     */
    public function addFiltersToQB(QueryBuilder $qb)
    {
        $this->applyFiltersToQb($qb);
    }

    /**
     *
     * @param \Doctrine\DBAL\Query\QueryBuilder $qb
     */
    public function addFiltersToDbalQB(\Doctrine\DBAL\Query\QueryBuilder $qb)
    {
        $this->applyFiltersToQb($qb);
    }

    private function _verifyDate($date)
    {
        return (\DateTime::createFromFormat('d.m.Y', $date) !== false);
    }

    /**
     * @return array
     */
    public function getFiltersValue()
    {
        $ret = [];
        foreach ($this->filterData as $filter) {
            $ret[$filter['field']] = [
                'value' => $filter['value'],
                'options' => $filter['options'],
                'type' => $filter['type'],
            ];
        }

        return $ret;
    }

    /**
     * @param QueryBuilder|\Doctrine\DBAL\Query\QueryBuilder $qb
     */
    private function applyFiltersToQb($qb)
    {
        foreach ($this->filterData as $key => $filter) {

            switch ($filter['type']) {
                case 'like':
                    if ($filter['value'] ) {
                        if (array_key_exists('ignore_chars', $filter) && !empty($filter['ignore_chars'])) {
                            $filter['value'] = str_replace($filter['ignore_chars'], '_', $filter['value']);
                        }

                        $qb
                            ->andWhere('LOWER(' . $filter['field'] . ') LIKE LOWER(:filter' . $key . ')')
                            ->setParameter('filter' . $key, '%' . $filter['value'] . '%');
                    }
                    break;
                case 'int':
                    if ($filter['value'] === '0' || $filter['value'] === 0 || (int)$filter['value']) {

                        $int = (int)$filter['value'];
                        $qb
                            ->andWhere($filter['field'] . ' = :filter' . $key)
                            ->setParameter('filter' . $key, $int);
                    }
                    break;
                case 'float':
                    if ($filter['value'] === '0' || $filter['value'] === 0 || (float)$filter['value']) {

                        $float = (float)$filter['value'];
                        $qb
                            ->andWhere($filter['field'] . ' = :filter' . $key)
                            ->setParameter('filter' . $key, $float);
                    }
                    break;
                case 'eq':
                    if ($filter['value']) {
                        if ($filter['value'] !== null && strtolower($filter['value']) !== 'null') {

                            $qb
                                ->andWhere($filter['field'] . ' = :filter' . $key)
                                ->setParameter('filter' . $key, $filter['value']);
                        } else {

                            $qb
                                ->andWhere($filter['field'] . ' IS NULL');
                        }
                    }

                    break;
                case 'instance':
                    if ($filter['value']) {
                        $qb->andWhere($filter['field'] . ' INSTANCE OF :filter' . $key)
                            ->setParameter('filter' . $key, $filter['value']);
                    }
                    break;
                case 'date':
                    if ($this->_verifyDate($filter['value'])) {
                        $dtA = \DateTime::createFromFormat('d.m.Y', $filter['value']);
                        $dtA->setTime(0, 0, 0);
                        $dtB = \DateTime::createFromFormat('d.m.Y', $filter['value']);

                        if (is_object($dtB)) {
                            $dtB->modify('+1 day');
                            $dtB->setTime(0, 0, 0);

                            $qb
                                ->andWhere($filter['field'] . ' >= :filterA' . $key)
                                ->setParameter('filterA' . $key, $dtA->format('Y-m-d'))
                                ->andWhere($filter['field'] . ' < :filterB' . $key)
                                ->setParameter('filterB' . $key, $dtB->format('Y-m-d'));
                        }
                    }
                    break;
                case 'closure':
                    if ($filter['value']) {
                        /** @var \Closure $closure */
                        $closure = $filter['closure'];
                        $closure($qb, $filter, $key);
                        break;
                    }
            }

        }
    }

}