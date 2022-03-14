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

    public function GetRent($idResidences): array
    {
        $date = date('Y-m-d');
        foreach ($idResidences as $key => $idResidence) {
            $val[] = $this->createQueryBuilder('rent')
                ->select('count(rent.id) as nbLocationTotal ,r.name, r.lastName, rent.available, residence.name as residenceName ,t.id as tenantId')
                ->innerJoin('rent.tenant', 't')
                ->innerJoin('rent.residence', 'residence')
                ->innerJoin('residence.representative', 'r')
                ->where('rent.residence = :idResidence')
                ->setParameter('idResidence', $idResidence)
                ->getQuery()
                ->getResult();
        }
        return $val;
    }

    public function getIdResidences(): array
    {
        return $this->createQueryBuilder('rent')
            ->select("DISTINCT(r.id)")
            ->innerJoin('rent.residence', 'r')
            ->getQuery()
            ->getResult();
    }

    public function GetTotalResidence($val): array
    {
        return $this->createQueryBuilder('r')
            ->select("COUNT(r.id)")
            ->where('r.available >= :val')
            ->setParameter('val', $val)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getTotalLocation($values): array
    {
        foreach ($values as $key => $value) {
            $val[] = $this->createQueryBuilder('rent')
                ->select('count(rent.id) as totalLocation')
                ->innerJoin('rent.residence', 'r')
                ->andWhere('r.id in (:val)')
                ->setParameter('val', $value)
                ->getQuery()
                ->getResult();
        }
        return $val;
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
