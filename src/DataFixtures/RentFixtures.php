<?php

namespace App\DataFixtures;

use App\Entity\Rent;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //création d'un timestamp aléatoire
        $timestamp = mt_rand(1, time());
        $date = new \DateTime("2009-04-10");
        $date->setTimestamp($timestamp);

        $rent = new Rent();
        $rent->setTenantId($this->getReference('user-'.rand(5,7)));
        $rent->setResidenceId($this->getReference('residence-'.rand(1,5)));
        $rent->setInventoryFile("test");
        $rent->setOwner($this->getReference('user-'.rand(8,10)));
        $rent->setAvailable(new \DateTime());
        $rent->setArrivalDate(new \DateTime());
        $rent->setDepartureDate(new \DateTime());
        $rent->setTenantSignature("yes");
        $rent->setTenantCillents("test");
        $rent->setTenantValidatedAt("now");
        $rent->setRepresntativeComments("test");
        $rent->setRepresntativeSignature("test");
        $rent->setRepresntativeValidatedAt(new \DateTime());

        $manager->persist($rent);
        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            ResidenceFixtures::class,
            UserFixtures::class,
        ];
    }
}
