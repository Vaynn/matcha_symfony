<?php

namespace App\DataFixtures;

use App\Entity\Sexuality;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SexualityFixture extends Fixture {

    public function load(ObjectManager $manager): void
    {
        $sexualities = [
            'Heterosexual' => 'Romantic or sexual attraction to the opposite gender.',
            'Homosexual' => 'Romantic or sexual attraction to the same gender.',
            'Bisexual' => 'Romantic or sexual attraction to both genders.',
            'Pansexual' => 'Romantic or sexual attraction to individuals regardless of their gender or gender identity.',
            'Asexual' => 'Lack of sexual attraction to others.',
            'Demisexual' => 'Sexual attraction that develops after an emotional connection.',
            'Graysexual' => 'A state between asexuality and sexuality, where sexual attraction is low or variable.',
            'Polysexual' => 'Romantic or sexual attraction to several, but not all, genders.',
            'Queer' => 'An umbrella term that can include a variety of sexual and gender identities.',
            'Questioning' => 'In exploration or questioning of sexual orientation.',
            'Fluid' => 'A sexual orientation that may change over time.',
            'Androsexual' => 'Attraction to men, regardless of the gender identity of the person attracted to.',
            'Gynosexual' => 'Attraction to women, regardless of the gender identity of the person attracted to.',
            'Skoliosexual' => 'Attraction to non-binary or transgender individuals.',
            'Aromantic' => 'Lack of romantic attraction to others.',
            'Biromantic' => 'Romantic attraction to both genders.',
            'Heteroromantic' => 'Romantic attraction to the opposite gender.',
            'Homoromantic' => 'Romantic attraction to the same gender.',
            'Panromantic' => 'Romantic attraction to individuals regardless of their gender or gender identity.',
            'Demiromantic' => 'Romantic attraction that develops after an emotional connection.',
            'Grayromantic' => 'A state between aromanticism and romanticism, where romantic attraction is low or variable.',
            'Polyromantic' => 'Romantic attraction to several, but not all, genders.',
            'Other' => 'A category allowing individuals to define their romantic orientation in their own terms.'
        ];
        foreach ($sexualities as $sexualityName => $description){
            $sexuality = new Sexuality();
            $sexuality->setName($sexualityName);
            $sexuality->setDescription($description);
            $manager->persist($sexuality);
        }
        $manager->flush();

    }
}
