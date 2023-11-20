<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\ForgetPasswordType;
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
    /**
     * This function displays a registration form and sends a confirmation email to the user.
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param EmailService $mailer
     * @param TokenGeneratorService $tokenGenerator
     * @return Response
     */
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

    /**
     * This function is triggered when the user clicks on the link received via email and activates the account.
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
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

    /**
     * This function displays a form that requests the username in case of a forgotten password and sends an email
     * with a personalized link for changing the password.
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param TokenGeneratorService $tokenGenerator
     * @param EmailService $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    #[Route('/new/password', name: 'security.new.password', methods: ['GET', 'POST'])]
    public function forgetPassword(Request $request,
                                   EntityManagerInterface $manager,
                                   TokenGeneratorService $tokenGenerator,
                                   EmailService $mailer){
        $form = $this->createForm(ForgetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $user = $manager->getRepository(User::class)->findOneBy(['username' => $form->get('username')->getData()]);
            if ($user){
                $user->setTokenActivation($tokenGenerator->generateToken(64));
                $this->addFlash(
                    'success',
                    'Email sent.'
                );

                $manager->flush();
                $mailer->sendchangePwdEmail($user->getEmail(), $user->getTokenActivation(), $user->getUsername());

                return $this->redirectToRoute('security.login');
            }
            else {
                $this->addFlash(
                    'error',
                    'We couldn\'t find that username. Please verify and re-enter.'
                );
            }
        }
        return $this->render('pages/security/forgetPassword.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('/update/password', name: 'security.update.password', methods: ['GET', 'POST'])]
    public function changePassword(Request $request, EntityManagerInterface $manager){
        $repository = $manager->getRepository(User::class);
        $user = $repository->findOneBy([
            'username' => $request->get('username'),
            'tokenActivation' => $request->get('token')]);
        if ($user){
            $form = $this->createForm(ChangePasswordType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $user->setTokenActivation(null);
                $user->setPlainPassword($form->get('plainPassword')->getData());
                $manager->persist($user);
                $manager->flush();
                return $this->redirectToRoute('security.login');
            }
            return $this->render('pages/security/change_password.html.twig',
            ['form' => $form]);
        } else {
            $this->addFlash(
                'error',
                'FORBIDDEN LINK'
            );
            return $this->redirectToRoute('security.login');
        }
    }
}
