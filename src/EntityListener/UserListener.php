<?php

namespace App\EntityListener;

use App\Entity\User;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher){
        $this->hasher = $hasher;
    }
    public function prePersist(User $user){
        $this->encodePassword($user);
    }

    public function preUpdate(User $user){
        $this->encodePassword($user);
    }

    public function preFlush($user, PreFlushEventArgs $args){
        $this->encodePassword($user);
    }

    public function encodePassword(User $user){
        if ($user->getPlainPassword() === null){
            return;
        }
        $user->setPassword(
            $this->hasher->hashPassword(
                $user,
                $user->getPlainPassword()
            )
        );
        $user->setPlainPassword(null);

    }
}