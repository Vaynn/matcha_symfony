<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search/{id}', name: 'search.search', methods:['GET'])]
    public function search(UserRepository $userRepository, Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }
        $user = $this->getUser();
        $preferences = $user->getPreferences();
        if ($preferences)
            $matches = $userRepository->findByPreferences($preferences, $request);
        else
            $matches = $userRepository->findAllExceptCurrentUser($user, $request);

        return $this->render('search/search.html.twig', [
            'controller_name' => 'SearchController',
            'user' => $user,
            'matches' => $matches
        ]);
    }
}
