<?php


namespace App\Manager\Formatter;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class CollectionFormatter
{
    /** @var EntityManagerInterface*/
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $entityName
     * @param string $keyProperty
     * @param string $valueProperty
     * @return array
     */
    public function toAssocArray(
        string $entityName,
        string $keyProperty,
        string $valueProperty,
        $order = []
    ) : array
    {
        $accessor = new PropertyAccessor();
        $result = [];

        $items = $this->em->getRepository($entityName)->findBy([], $order);

        foreach ($items as $index => $item) {
            $key = $accessor->getValue($item, $keyProperty);
            $value = $accessor->getValue($item, $valueProperty);

            $result[$index]['id'] = $key;
            $result[$index]['title'] = $value;
        }

        return $result;
    }


}