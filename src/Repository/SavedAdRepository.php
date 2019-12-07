<?php

namespace App\Repository;

use App\Entity\SavedAd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SavedAd|null find($id, $lockMode = null, $lockVersion = null)
 * @method SavedAd|null findOneBy(array $criteria, array $orderBy = null)
 * @method SavedAd[]    findAll()
 * @method SavedAd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SavedAdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SavedAd::class);
    }

    // /**
    //  * @return SavedAd[] Returns an array of SavedAd objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SavedAd
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
