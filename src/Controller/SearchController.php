<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\Clock\now;

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

    #[Route('/new-crush', name: 'new_crush', methods: ['POST'])]
    public function newCrush(Request $request, EntityManagerInterface $manager){
        if (!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }
        $crushId = json_decode($request->getContent(), true)['userId'];
        $user = $this->getUser();
        $crush = $manager->getRepository(User::class)->findOneBy(['id' => $crushId]);
        $like = new Like();
        $like->setIsLiked($crush);
        $like->setLikedBy($user);
        $like->setLikedAt(new \DateTimeImmutable());
        $manager->persist($like);
        $manager->flush();
        return new JsonResponse(['success' => true]);

    }
    #[Route('/delete-crush', name: 'delete_crush', methods: ['POST'])]
    public function deleteCrush(Request $request, EntityManagerInterface $manager){
        if (!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }
        $crushId = json_decode($request->getContent(), true)['userId'];
        $crush = $manager->getRepository(User::class)->findOneBy(['id' => $crushId]);
        $user = $this->getUser();
        $like = $manager->getRepository(Like::class)->findOneBy(['likedBy' => $user, 'isLiked' => $crush]);
        $manager->remove($like);
        $manager->flush();
        return new JsonResponse(['success' => true]);

    }


}
