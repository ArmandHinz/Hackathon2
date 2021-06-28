<?php

namespace App\DataFixtures;

use App\Entity\Techno as Technologie;
use Faker;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TechnologieFixtures extends Fixture
{
    public const TECH = [
        'CSS',
        'PHP',
        'HTML',
        'SYMFONY',
        'BDD'
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for($i = 0;$i<count(self::TECH); $i++) {
            $tech = new Technologie();
            $tech->setName(self::TECH[$i]);
            $manager->persist($tech);
        }
        $manager->flush();
    }
}