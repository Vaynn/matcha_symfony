<?php

namespace App\DataFixtures;

 use App\Entity\Gender;
 use Doctrine\Bundle\FixturesBundle\Fixture;
 use Doctrine\Persistence\ObjectManager;

 class GenderFixture extends Fixture {

     public function load(ObjectManager $manager): void
     {
         $genders = [
             'Male' => 'An individual who identifies as a man.',
             'Female' => 'An individual who identifies as a woman.',
             'Non-binary' => 'An identity that does not exclusively fit within the categories of male or female.',
             'Agender' => 'A person who does not identify with any gender.',
             'Bigender' => 'A person who identifies with two genders, either simultaneously or at different times.',
             'Demiboy' => 'A person whose gender identity is partially, but not wholly, male.',
             'Demigirl' => 'A person whose gender identity is partially, but not wholly, female.',
             'Genderfluid' => 'A person whose gender identity may change or be fluid.',
             'Genderqueer' => 'A term used by some people to describe a gender identity that does not conform to traditional binary norms.',
             'Two-Spirit' => 'A term used by some Indigenous cultures to describe a person embodying both masculine and feminine qualities.',
             'Androgynous' => 'A person with a gender expression that combines both masculine and feminine characteristics.',
             'Pangender' => 'A person whose gender identity encompasses all genders.',
             'Neutrois' => 'A person whose gender identity is neutral or null, not aligned with any gender.',
             'Third Gender' => 'A category for genders outside the traditional male and female binary.',
             'Cisgender' => 'A person whose gender identity matches the sex assigned at birth.',
             'Transgender' => 'A person whose gender identity differs from the sex assigned at birth.',
             'Questioning' => 'A person who is exploring or questioning their gender identity.',
             'Other' => 'A category allowing individuals to define their gender identity in their own terms.'
         ];
         foreach ($genders as $genderName => $definition){
             $gender = new Gender();
             $gender->setName($genderName);
             $gender->setDescription($definition);
             $manager->persist($gender);
         }
         $manager->flush();
     }
 }