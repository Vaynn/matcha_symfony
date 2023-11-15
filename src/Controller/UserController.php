<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Tag;
use App\Entity\User;
use App\Form\EditUserPhotosType;
use App\Form\EditUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function edit(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        if (!$this->getUser()){
            return $this->redirectToRoute('security.login');
        }
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $entityManager->flush();
            return $this->redirectToRoute('user.show', ['id' => $user->getId()]);
        }
        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('user/edit/photos/{id}', name: 'user.edit.photos', methods: ['GET', 'POST']) ]
    public function editPhotos(Request $request, EntityManagerInterface $entityManager, User $user, SluggerInterface $slugger): Response
    {
        if (!$this->getUser()){
            return $this->redirectToRoute('security.login');
        }
        $form = $this->createForm(EditUserPhotosType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $imageFile = $form->get('photo')->getData();
            $originalImageName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = $slugger->slug($originalImageName);
            $newFilename = $safeFileName.'-'.uniqid().'.'.$imageFile->guessExtension();
            try {
                $imageFile->move(
                    $this->getParameter('photos_directory'),
                    $newFilename
                );
                $image = new Image();
                $image->setName($newFilename)
                    ->setUserId($user)
                    ->setIsProfileImage(false);
                $entityManager->persist($image);
                $entityManager->flush();
                return $this->redirectToRoute('user.edit.photos', parameters: ['id' => $user->getId()]);
            } catch (FileException $e) {

            }
        }
        return $this->render('pages/user/edit_photos.html.twig',
        [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('user/delete/photo/{photoId}', name: 'user.delete.photo', methods: ['GET', 'DELETE']) ]
    public function deletePhoto(Request $request, EntityManagerInterface $manager, FileSystem $filesystem){
        if (!$this->getUser()){
            return $this->redirectToRoute('security.login');
        }
        $imageId = $request->attributes->get('photoId');
        $image = $manager->getRepository(Image::class)->find($imageId);
        $imageName = $image->getName();
        $manager->remove($image);
        $manager->flush();
        $imageDir = $this->getParameter('photos_directory');
        $filesystem->remove($imageDir . $imageName);
        return $this->redirectToRoute('user.edit.photos', parameters: ['id' => $this->getUser()->getId()]);

    }
    #[Route('user/update/photo/{photoId}', name: 'user.update.photo', methods: ['GET', 'POST']) ]
    public function updatePhoto(Request $request, EntityManagerInterface $manager){
        if (!$this->getUser()){
            return $this->redirectToRoute('security.login');
        }
        $imageId = $request->attributes->get('photoId');
        $newImageProfile = $manager->getRepository(Image::class)->find($imageId);
        $previousImageProfile = $manager->getRepository(Image::class)->findOneBy([
            'is_profile_image' => true,
            'user_id'=>$this->getUser()->getId()]);
        $newImageProfile->setIsProfileImage(true);
        if ($previousImageProfile){
            $previousImageProfile->setIsProfileImage(false);
            $manager->persist($previousImageProfile);
        }
        $manager->persist($newImageProfile);
        $manager->flush();
        return $this->redirectToRoute('user.edit.photos', parameters: ['id' => $this->getUser()->getId()]);

    }
}
