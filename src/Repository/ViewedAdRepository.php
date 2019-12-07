<?php

namespace App\Repository;

use App\Entity\ViewedAd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ViewedAd|null find($id, $lockMode = null, $lockVersion = null)
 * @method ViewedAd|null findOneBy(array $criteria, array $orderBy = null)
 * @method ViewedAd[]    findAll()
 * @method ViewedAd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViewedAdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ViewedAd::class);
    }

    // /**
    //  * @return ViewedAd[] Returns an array of ViewedAd objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ViewedAd
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
