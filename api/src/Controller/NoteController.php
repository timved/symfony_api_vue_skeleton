<?php


namespace App\Controller;

use App\Entity\User;
use App\Filter\Filter;
use App\Manager\NoteManager;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends AbstractFOSRestController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var NoteRepository
     */
    private $noteRepository;
    /**
     * @var NoteManager
     */
    private $noteManager;

    /**
     * NoteController constructor.
     * @param EntityManagerInterface $entityManager
     * @param NoteRepository $noteRepository
     * @param NoteManager $noteManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        NoteRepository $noteRepository,
        NoteManager $noteManager
    )
    {
        $this->entityManager = $entityManager;
        $this->noteRepository = $noteRepository;
        $this->noteManager = $noteManager;
    }

    /**
     *
     * @param Request $request
     * @param Filter $filter
     * @param User $user
     * @return JsonResponse
     * @Rest\Get("/api/user/{id}/notes", name="user_notes")
     */
    public function userNotes(Request $request, Filter $filter, User $user)
    {
//        if ($user){
            $filter->getFilter($request, $this->noteManager->getNotesFilters());
            $result = $this->noteManager->getNotesPaginate(
                $request->query->get('page', 1),
                $request->query->get('itemsPerPage', 10),
                $filter,
                $user);
            return new JsonResponse($result, 200, [], true);
//        }

//        return $this->view(['message' => 'Something went wrong'], Response::HTTP_BAD_REQUEST);
    }
}