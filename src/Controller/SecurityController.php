<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Service\EmailService;
use App\Service\TokenGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/registration', name:'security.registration', methods: ['GET', 'POST'])]
    public function registration(
        Request $request,
        EntityManagerInterface $manager,
        EmailService $mailer,
        TokenGeneratorService $tokenGenerator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $user->setRoles(['ROLE_USER']);
            $user->setIsActive(false);
            $user->setTokenActivation($tokenGenerator->generateToken(64));

            $this->addFlash(
                'success',
                'An email has been sent to you. Please confirm your registration, before connexion.'
            );

            $manager->persist($user);
            $manager->flush();

            $mailer->sendRegistrationEmail($user->getEmail(), $user->getTokenActivation(), $user->getUsername());

            return $this->redirectToRoute('security.login');

        }
        return $this->render('pages/security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/activation', name:'security.activation', methods: ['GET'])]
    public function confirmRegistration(Request $request, EntityManagerInterface $manager) : Response {

        $repository = $manager->getRepository(User::class);
        $user = $repository->findOneBy([
            'username' => $request->get('username'),
            'tokenActivation' => $request->get('token')
        ]);
        if ($user != null){
            $user->setIsActive(true);
            $user->setTokenActivation(null);
            $this->addFlash(
                'success',
                'Congratulation ! Connect you and start to chat !'
            );
            return $this->redirectToRoute('security.login');
        }
        $this->addFlash(
            'error',
            'Error ! Your inscription has failed !'
        );
        return $this->redirectToRoute('security.login');
    }

    #[Route('/connexion', name:'security.login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authentication, Request $request): Response
    {
        return $this->render('pages/security/login.html.twig', [
            'last_username' => $authentication->getLastUsername(),
            'error' => $authentication->getLastAuthenticationError()
        ]);
    }


    #[Route('/logout', name:'security.logout')]
    public function logout(){}


}
