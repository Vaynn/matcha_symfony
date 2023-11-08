<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(){
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        $sexuality = [
            1 => "Straight",
            2 => "Homo",
            3 => "Bi"
        ];

        for ($i=0; $i < 50; $i++){
            $gender = mt_rand(0, 1) == 1 ? 'male' : 'female';
            $user = new User();
            $user->setUsername($this->faker->unique()->word())
                ->setEmail($this->faker->email())
                ->setFirstName($this->faker->firstName($gender))
                ->setLastName($this->faker->lastName())
                ->setGender($gender)
                ->setSexuality($sexuality[mt_rand(1, 3)])
                ->setBiography($this->faker->text(150))
                ->setTags($this->faker->words(3))
                ->setFameRating(mt_rand(0, 5))
                ->setRoles(['ROLE_USER'])
                ->setAge(mt_rand(18, 99))
                ->setPlainPassword('password');
            $image = new Image();
            $image->setName($user->getGender() === "male" ? "male/".$i : "female/".$i)
                ->setUserId($user)
                ->setIsProfileImage(true);
            $manager->persist($user);
            $manager->persist($image);
        }

        $manager->flush();
    }
}
