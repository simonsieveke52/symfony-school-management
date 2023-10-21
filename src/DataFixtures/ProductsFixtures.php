<?php

namespace App\DataFixtures;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Student;
use App\Entity\StdProfile;


class ProductsFixtures extends Fixture
{   
    private $encoder;

    function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        
        $user = new User();
        $finStd = new Student();
        $stdProfile = new StdProfile();
        $stdProfile->setState('NOT VERIFIED')
                    ->setNote(0)
        ;

        $user->setName("admin")
             ->setEmail("admin@gmail.com")
             ->setPicture('Pretty-Camera-202059201333110-5ee7db420c556.jpeg')
             ->setPassword($this->encoder->encodePassword($user, "123456"))
             ->setRoles(["ROLE_ADMIN", "ROLE_USER"])
        ;

        $finStd->setUser($user);
        $finStd->setProfile($stdProfile)
               ;
                  
                 
        $manager->persist($finStd);
        $manager->persist($stdProfile);

        $manager->persist($user);

        $user = new User();
        $finStd = new Student();
        $stdProfile = new StdProfile();
        $stdProfile->setState('NOT VERIFIED')
                    ->setNote(0)
        ;

        $user->setName("user")
             ->setEmail("user@gmail.com")
             ->setPicture('Pretty-Camera-202059201333110-5ee7db420c556.jpeg')
             ->setPassword($this->encoder->encodePassword($user, "123456"))
             ->setRoles(["ROLE_USER"])
        ;

         $finStd->setUser($user);
        $finStd->setProfile($stdProfile)
               
               ;

        $manager->persist($finStd);
        $manager->persist($stdProfile);

        $manager->persist($user);


       
         
     
      

        

        $manager->flush();
    }
}
