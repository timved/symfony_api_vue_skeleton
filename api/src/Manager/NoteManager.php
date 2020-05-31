<?php


namespace App\Manager;

use App\Entity\Note;
use App\Entity\User;
use App\Manager\Formatter\CollectionFormatter;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use App\Filter\Filter;
use Knp\Component\Pager\PaginatorInterface;


class NoteManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var CollectionFormatter $formatter
     */
    private $formatter;

    /**
     * @var array
     */
    private $noteFilters = [
        'n.created' => 'date',
        'n.updated' => 'date',
        'n.title' => 'like',
        'n.text' => 'like'
    ];
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @param CollectionFormatter $formatter
     * @param SerializerInterface $serializer
     */
    public function __construct(
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        CollectionFormatter $formatter,
        SerializerInterface $serializer
    ) {
        $this->em = $em;
        $this->paginator = $paginator;
        $this->formatter = $formatter;
        $this->serializer = $serializer;
    }

    public function getNotesFilters()
    {
        $filters = $this->noteFilters;

        return $filters;
    }

    /**
     * @param $page
     * @param int $limit
     * @param Filter $filter
     * @param User|null $user
     * @return string
     */
    public function getNotesPaginate($page, int $limit, Filter $filter, User $user = null) : string
    {
        $select = [
            'n.id',
            'n.created',
            'n.updated',
            'n.title',
            'n.text'
        ];

        $qb = $this->em->createQueryBuilder()
            ->select($select)
            ->from(Note::class,'n')
            ->andWhere('n.user = :user')
            ->setParameter('user', $user)
        ;

        $filter->addFiltersToQB($qb);

        $filters['filters'] = $this->getNotesFilters();
        $paginator = $this->paginator->paginate($qb, $page, $limit);
        $jsonPaginator = $this->serializer->serialize($paginator, 'json');

        return json_encode(array_merge(json_decode($jsonPaginator, true), $filters));
    }

}