<?php

namespace App\Repository;

use App\Entity\Rent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rent[]    findAll()
 * @method Rent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rent::class);
    }

    public function GetRent(): array
    {
        return $this->createQueryBuilder('rent')
            ->innerJoin('rent.tenant', 't')
            ->innerJoin('rent.residence','residence')
            ->getQuery()
            ->getResult();
    }
    public function GetTotalResidence() : array
    {
        return $this->createQueryBuilder('r')
            ->select("COUNT(r.id)")
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function countNbResidence($values): array
    {
        foreach ($values as $key => $value) {
            $val[] = $this->createQueryBuilder('r')
                ->select('count(r.id) as totalResidence,u.name , u.lastName , u.id')
                ->innerJoin('r.representative', 'u')
                ->andWhere('u.id in (:val)')
                ->setParameter('val', $value)
                ->getQuery()
                ->getResult();
        }
        return $val;
    }

    // /**
    //  * @return Rent[] Returns an array of Rent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Rent
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
