<?php

namespace App\DataFixtures;

use App\Entity\Projet as EntityProjet;
use Faker;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProjetFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
       $faker = Faker\Factory::create('fr_FR');

       $iteration = 10 ;
       for($i = 0 ; $i < $iteration ; $i++) {
           $projet = new EntityProjet();
           $projet->setDescription($faker->text);
           $projet->setName($faker->name($gender = null));
           $projet->setUrlDrive($faker->url);

           $manager->persist($projet);
       }

        $manager->flush();
    }

}
