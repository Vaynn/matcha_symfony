<?php

namespace App\Controller;

use App\Entity\Like;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CrushController extends AbstractController
{
    #[Route('/crush', name: 'crush', methods: ['GET'])]
    public function crush(EntityManagerInterface $manager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }
        $user = $this->getUser();
        if ($user->getHaveNewNotif()){
            $user->setHaveNewNotif(false);
            $manager->persist($user);
            $manager->flush();
        }
        $interested = $manager->getRepository(Like::class)->findBy(['isLiked' => $user], ['likedAt' => 'DESC']);
        $my_favorites = $manager->getRepository(Like::class)->findBy(['likedBy' => $user], ['likedAt' => 'DESC']);

        return $this->render('crush/crush.html.twig', [
            'controller_name' => 'CrushController',
            'interested' => $interested,
            'favorites'  => $my_favorites,
            'user' => $user
        ]);
    }
}
