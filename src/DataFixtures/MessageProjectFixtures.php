<?php

namespace App\DataFixtures;

use App\Entity\MessageProjet;
use Faker;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MessageProjectFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
       $faker = Faker\Factory::create('fr_FR');

       $iteration = 10 ;
       for($i = 0 ; $i < $iteration ; $i++) {
           $message= new MessageProjet();
           //$message->setUser($faker->name($gender = 'male'));
           $message->setDate($faker->DateTime('2008-04-25 08:37:17'));
           $message->setContent($faker->text());

           $manager->persist($message);
       }

        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            ProjetFixtures::class
        ];
    }
}
