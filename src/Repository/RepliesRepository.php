<?php

namespace App\Repository;

use App\Entity\Replies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Replies|null find($id, $lockMode = null, $lockVersion = null)
 * @method Replies|null findOneBy(array $criteria, array $orderBy = null)
 * @method Replies[]    findAll()
 * @method Replies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepliesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Replies::class);
    }

    // /**
    //  * @return Replies[] Returns an array of Replies objects
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
    public function findOneBySomeField($value): ?Replies
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
