<?php

namespace App\Repository;

use App\Entity\Residence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Residence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Residence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Residence[]    findAll()
 * @method Residence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResidenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Residence::class);
    }

    public function countNbResidence($values): array
    {
        foreach ($values as $value) {
            return $this->createQueryBuilder('r')
                ->select('count(r.id) as totalResidence,u.name , u.lastName , u.id')
                ->innerJoin('r.representative', 'u')
                ->andWhere('r.representative = :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getResult();
        }
    }

    public function GetResidence($value): array
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.representative', 'u')
            ->andWhere('r.representative = :val')
            ->setParameter('val', $value)
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



    // /**
    //  * @return Residence[] Returns an array of Residence objects
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
    public function findOneBySomeField($value): ?Residence
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
