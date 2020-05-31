<?php


namespace App\Controller;

use App\Entity\User;
use App\Repository\Dictionary\SubjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractFOSRestController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var SubjectRepository
     */
    private $subjectRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * ExampleController constructor.
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param SubjectRepository $subjectRepository
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        SubjectRepository $subjectRepository,
        UserPasswordEncoderInterface $encoder
    )
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->subjectRepository = $subjectRepository;
        $this->encoder = $encoder;
    }

    /**
     * @Rest\Route("/api/user/info", name="user_info")
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function userInfo(Request $request)
    {
        $email = $request->get('email');
        $data = $this->userRepository->findOneBy(['email' => $email]);
        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * @param ParamFetcher $paramFetcher
     * @param User $user
     * @Rest\Post("/api/user/update/{id}", name="update_user")
     * @Rest\RequestParam(name="login", description="Login of the User", nullable=false)
     * @Rest\RequestParam(name="fio", description="FIO of the User", nullable=false)
     * @Rest\RequestParam(name="subject", description="Subject code of the User", nullable=false)
     * @Rest\RequestParam(name="password", description="Password of the User", nullable=true)
     * @return \FOS\RestBundle\View\View
     */
    public function updateUser(ParamFetcher $paramFetcher, User $user)
    {
        $login = $paramFetcher->get('login');
        $fio = $paramFetcher->get('fio');
        $subjectCode = $paramFetcher->get('subject');
        $subject = $this->subjectRepository->findOneBy(['code' => $subjectCode]);
        $password = $paramFetcher->get('password');

        if ($user){
            $user->setLogin($login);
            $user->setFio($fio);
            $user->setSubject($subject);
            if (!is_null($password)) $user->setPassword(
                $this->encoder->encodePassword($user, $password)
            );
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->view($user, Response::HTTP_OK);
        }

        return $this->view(['message' => 'Something went wrong'], Response::HTTP_BAD_REQUEST);
    }

}