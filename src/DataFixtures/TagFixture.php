<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixture extends Fixture{
    public function load(ObjectManager $manager)
    {
        $hashtags = [
            'AdventureSeeker',
            'BookLover',
            'CoffeeAddict',
            'DogLover',
            'FitnessJunkie',
            'Foodie',
            'GamerLife',
            'GlobeTrotter',
            'HealthNut',
            'MovieBuff',
            'MusicLover',
            'NatureLover',
            'NightOwl',
            'YogaEnthusiast',
            'TechGeek',
            'ArtAficionado',
            'BeachBum',
            'VintageSoul',
            'WellnessWarrior',
            'WorkoutWarrior',
            'SkiingAdventures',
            'PassionatePhotographer',
            'ThrillSeeker',
            'MindfulExplorer',
            'SoccerFanatic',
            'Wanderlust',
            'MindfulMeditator',
            'KaraokeKing',
            'Snowboarder',
            'ArtisticSoul',
            'ScienceNerd',
            'GardeningGuru',
            'CulinaryExplorer',
            'RunningAddict',
            'Fashionista',
            'TechInnovator',
            'OutdoorEnthusiast',
            'StarGazer',
            'FitnessExplorer',
            'VeganLife',
            'DIYChampion',
            'StartupDreamer',
            'Bookworm',
            'CraftBeerConnoisseur',
            'CyclistLife',
            'EcoWarrior',
        ];

        foreach ($hashtags as $tagName){
            $tag = new Tag();
            $tag->setName($tagName);
            $manager->persist($tag);
        }
        $manager->flush();
    }
}