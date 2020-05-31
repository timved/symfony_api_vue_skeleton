<?php


namespace App\Controller;

use App\Repository\Dictionary\SubjectRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class DictionaryController extends AbstractFOSRestController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SubjectRepository
     */
    private $dictSubjectRepository;

    /**
     * ExampleController constructor.
     * @param EntityManagerInterface $entityManager
     * @param SubjectRepository $dictSubjectRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        SubjectRepository $dictSubjectRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->dictSubjectRepository = $dictSubjectRepository;
    }

    /**
     * @Rest\Get("/api/public/dictionary/subjects", name="dictionary_subjects")
     */
    public function subjects()
    {
        $data = $this->dictSubjectRepository->findAll();
        return $this->view($data, Response::HTTP_OK);
    }

}