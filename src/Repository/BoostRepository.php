<?php

namespace App\Repository;

use App\Entity\Boost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Boost|null find($id, $lockMode = null, $lockVersion = null)
 * @method Boost|null findOneBy(array $criteria, array $orderBy = null)
 * @method Boost[]    findAll()
 * @method Boost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Boost::class);
    }

    // /**
    //  * @return Boost[] Returns an array of Boost objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Boost
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
