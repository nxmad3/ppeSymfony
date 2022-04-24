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

    public function findAllRentsById(int $id)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    public function GetRent($idResidences): array
    {
        foreach ($idResidences as $key => $idResidence) {
            $val[] = $this->createQueryBuilder('rent')
                ->select('count(rent.id) as nbLocationTotal ,r.name, r.lastName, rent.arrival_date, residence.name as residenceName ,t.id as tenantId, residence.id as residenceId, residence.file as residenceFile ')
                ->innerJoin('rent.tenant', 't')
                ->innerJoin('rent.residence', 'residence')
                ->innerJoin('residence.Representative', 'r')
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
            ->where('r.arrival_date >= :val')
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
        $date = date('Y-m-d');
        foreach ($values as $key => $value) {
            $val[] = $this->createQueryBuilder('r')
                ->select('count(r.id) as totalResidence,u.name , u.lastName , u.id')
                ->innerJoin('r.residence', 'res')
                ->innerJoin('res.Representative', 'u')
                ->where('r.arrival_date <= (:date)')
                ->andWhere('u.id in (:val)')
                ->setParameter('val', $value)
                ->setParameter('date', $date)
                ->getQuery()
                ->getResult();
        }
        return $val;
    }

    public function findLocationByUser(int $id)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.tenant = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
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
