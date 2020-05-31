<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\Dictionary\SubjectRepository;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractFOSRestController
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SubjectRepository
     */
    private $subjectRepository;
    /**
     * @var MailerService
     */
    private $mailerService;

    /**
     * RegistrationController constructor.
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $entityManager
     * @param SubjectRepository $subjectRepository
     * @param MailerService $mailerService
     */
    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $encoder,
        EntityManagerInterface $entityManager,
        SubjectRepository $subjectRepository,
        MailerService $mailerService
    )
    {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
        $this->entityManager = $entityManager;
        $this->subjectRepository = $subjectRepository;
        $this->mailerService = $mailerService;
    }

    /**
     * @Route("/api/register", name="register")
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function register(Request $request)
    {
        $subjectCode = $request->get('subject');
        $fio = $request->get('fio');
        $email = $request->get('email');
        $login = $request->get('login');

        $password = User::generatePassword();

        $subject = $this->subjectRepository->findOneBy(['code' => $subjectCode]);

        $user = $this->userRepository->findOneBy([
            'email' => $email
        ]);

        if (!is_null($user)){
            return $this->view([
                'message' => 'User already exists'
            ], Response::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setSubject($subject);
        $user->setLogin($login);
        $user->setFio($fio);
        $user->setEmail($email);
        $user->setPassword(
            $this->encoder->encodePassword($user, $password)
        );
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->mailerService->setTo($user->getEmail());
        $this->mailerService->setSubject("Регистрация на сайте");
        $textBody = $this->render('mail/registration.html.twig', [
            'login' => $user->getLogin(),
            'fio' => $user->getFio(),
            'region' => $user->getSubject()->getTitle(),
            'email' => $user->getEmail(),
            'password' => $password,
            'created' => $user->getCreatedString()
        ]);
        $this->mailerService->setText($textBody);
        $this->mailerService->send();

        return $this->view($user, Response::HTTP_CREATED)->setContext((new Context())->setGroups(['public']));

    }

    /**
     * @Route("/api/logout", name="logout")
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     * @throws \Doctrine\DBAL\DBALException
     */
    public function logout(Request $request)
    {
        $email = $request->get('email');

        $conn = $this->entityManager->getConnection();

        $sql = 'DELETE FROM public.refresh_tokens
            WHERE username = :user';

        $stmt = $conn->prepare($sql);
        $stmt->bindValue('user', $email);
        $stmt->execute();

        return $this->view($email , Response::HTTP_OK);
    }

}
