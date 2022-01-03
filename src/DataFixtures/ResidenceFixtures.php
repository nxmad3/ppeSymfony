<?php

namespace App\DataFixtures;

use App\Entity\Residence;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ResidenceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i <= 5; $i++) {
            $residence = new Residence();
            $residence->setOwnerId($this->getReference('user-' . rand(8, 10)));
            $residence->setRepresentativeId($this->getReference('user-' . rand(1, 3)));
            $residence->setName("laPladza");
            $residence->setAddress("13 rue truc");
            $residence->setCity("lyon");
            $residence->setZipCode(312312);
            $residence->setCountry("French");
            $residence->setInventoryFile("test?");
            $this->addReference('residence-' . $i, $residence);
            $manager->persist($residence);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}