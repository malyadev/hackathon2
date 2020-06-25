<?php

namespace App\Repository;

use App\Entity\PrescriptionDrug;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PrescriptionDrug|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrescriptionDrug|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrescriptionDrug[]    findAll()
 * @method PrescriptionDrug[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrescriptionDrugRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrescriptionDrug::class);
    }

    // /**
    //  * @return PrescriptionDrug[] Returns an array of PrescriptionDrug objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PrescriptionDrug
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
