<?php

namespace App\DataFixtures;

use App\Entity\Gender;
use App\Entity\Image;
use App\Entity\Sexuality;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class UserFixture extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');

    }

    public function load(ObjectManager $manager): void
    {
        $genders = $manager->getRepository(Gender::class)->findAll();
        $sexualities = $manager->getRepository(Sexuality::class)->findAll();
        $tags = $manager->getRepository(Tag::class)->findAll();

        for ($i = 0; $i < 100; $i++) {
            $gender = mt_rand(0, 1) == 1 ? $genders[1] : $genders[0];
            $sexuality = $sexualities[array_rand($sexualities)];
            $hashtags = array_rand($tags, mt_rand(2, 20));
            $user = new User();
            foreach ($hashtags as $hashtag){
                $user->addTag($tags[$hashtag]);
            }
            $user->setUsername($this->faker->unique()->word())
                ->setEmail($this->faker->email())
                ->setFirstName($this->faker->firstName(lcfirst($gender->getName())))
                ->setLastName($this->faker->lastName())
                ->setGender($gender)
                ->setSexuality($sexuality)
                ->setBiography($this->faker->text(199))
                ->setFameRating(mt_rand(0, 5))
                ->setRoles(['ROLE_USER'])
                ->setAge(mt_rand(18, 99))
                ->setPlainPassword('password');
            $image = new Image();
            $image->setName($user->getGender()->getName() === "Male" ? "male/" . $i : "female/" . $i)
                ->setUserId($user)
                ->setIsProfileImage(true);
            $manager->persist($user);
            $manager->persist($image);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            GenderFixture::class,
            SexualityFixture::class,
            TagFixture::class
        ];
    }
}




