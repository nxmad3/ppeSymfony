<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends fixture
{

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {

        //owner bailleur
        //tenant locataire
        //representative mandataire
        for ($i = 0; $i <= 10; $i++) {

            if ($i <= 3) {
                $user = new User();
                $user->setName("test".$i);
                $user->setLastName("TEST".$i);
                $user->setRole("representative");
                $user->setEmail("representative" . $i . "@root.fr");
//                $password = $this->hasher->hashPassword($user, 'test' . $i);
                $user->setPassword('test' . $i);
                $user->setIsVerified(true);
                $this->addReference('user-'.$i, $user);
                $manager->persist($user);
            } elseif ($i <= 6) {
                $user = new User();
                $user->setRole("tenant");
                $user->setEmail("tenant" . $i . "@root.fr");
//                $password = $this->hasher->hashPassword($user, 'test' . $i);
                $user->setPassword('test' . $i);
                $user->setIsVerified(true);
                $this->addReference('user-'.$i, $user);
                $manager->persist($user);
            } elseif ($i<= 8) {
                $user = new User();
                $user->setRole("owner");
                $user->setEmail("owner" . $i . "@root.fr");
//                $password = $this->hasher->hashPassword($user, 'test' . $i);
                $user->setPassword('test' . $i);
                $user->setIsVerified(true);
                $this->addReference('user-'.$i, $user);
                $manager->persist($user);
            }
            else {
                $user = new User();
                $user->setRole("owner");
                $user->setEmail("owner" . $i . "@root.fr");
//                $password = $this->hasher->hashPassword($user, 'test' . $i);
                $user->setPassword('test' . $i);
                $user->setIsVerified(false);
                $this->addReference('user-'.$i, $user);
                $manager->persist($user);
            }
        }
        $manager->flush();
    }
}
