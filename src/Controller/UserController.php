<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\User;
use App\Form\EditUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/show/{id}', name: 'user.show', methods: ['GET'])]
    public function show(User $user): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }
        if($this->getUser() !== $user){
            return $this->redirectToRoute('home.index');
        }
        return $this->render('pages/user/show.html.twig', [
            'controller_name' => 'UserController',
            'user' => $this->getUser()
        ]);
    }

    #[Route('/user/edit/{id}', name:'user.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        if (!$this->getUser()){
            return $this->redirectToRoute('security.login');
        }
        $form = $this->createForm(EditUserType::class, $user);

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
