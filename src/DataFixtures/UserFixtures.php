<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
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
                $user->setRoles(array("ROLE_REPRESENTATIVE"));
                $user->setEmail("representative" . $i . "@root.fr");
                $user->setIsVerified(false);
                $user->setName("test");
                $user->setLastName("test");
                $password = $this->hasher->hashPassword($user, 'test' . $i);
                $user->setPassword($password);

                $this->addReference('user-' . $i, $user);
                $manager->persist($user);
            } elseif ($i <= 6) {
                $user = new User();
                $user->setRoles(array("ROLE_TENANT"));
                $user->setEmail("tenant" . $i . "@root.fr");
                $user->setIsVerified(false);
                $user->setName("test");
                $user->setLastName("test");
                $password = $this->hasher->hashPassword($user, 'test' . $i);
                $user->setPassword($password);

                $this->addReference('user-' . $i, $user);
                $manager->persist($user);
            } elseif ($i <= 8) {
                $user = new User();

                $user->setRoles(array("ROLE_OWNER"));
                $user->setEmail("owner" . $i . "@root.fr");
                $user->setIsVerified(false);
                $user->setName("test");
                $user->setLastName("test");
                $password = $this->hasher->hashPassword($user, 'test' . $i);
                $user->setPassword($password);

                $this->addReference('user-' . $i, $user);
                $manager->persist($user);
            } else {
                $user = new User();
                $user->setRoles(array("ROLE_USER"));
                $user->setEmail("user" . $i . "@root.fr");
                $user->setIsVerified(false);
                $user->setName("test");
                $user->setLastName("test");
                $password = $this->hasher->hashPassword($user, 'test' . $i);
                $user->setPassword($password);
                $this->addReference('user-' . $i, $user);
                $manager->persist($user);
            }
        }
        $manager->flush();
    }
}