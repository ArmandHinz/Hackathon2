<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

     /**
      * @param UserPasswordHasherInterface $passwordHasher
      */
     public function __construct(UserPasswordHasherInterface $passwordHasher)
     {
         $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
         // Création d’un utilisateur de type “contributeur” (= auteur)
         $business = new User();
         $business->setFirstName('AK');
         $business->setLastname('ATON');
         $business->setDescription('voici ma description !');
         $business->setEmail('business@monsite.com');
         $business->setRoles(['ROLE_BUSINESS']);
         $business->setPassword($this->passwordHasher->hashPassword(
             $business,
             'businesspassword'
         ));
 
         $manager->persist($business);
 
         // Création d’un utilisateur de type “administrateur”
         $freelance = new User();
         $freelance->setFirstName('FRI');
         $freelance->setLastname('LANCE');
         $freelance->setDescription('voici ma description !');
         $freelance->setEmail('freelance@monsite.com');
         $freelance->setRoles(['ROLE_FREELANCE']);
         $freelance->setPassword($this->passwordHasher->hashPassword(
             $freelance,
             'freelancepassword'
         ));
 
         $manager->persist($freelance);
 
         // Sauvegarde des 2 nouveaux utilisateurs :
         $manager->flush();
        }
}
