<?php

namespace App\Repository;

use App\Entity\Friends;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Friends|null find($id, $lockMode = null, $lockVersion = null)
 * @method Friends|null findOneBy(array $criteria, array $orderBy = null)
 * @method Friends[]    findAll()
 * @method Friends[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FriendsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Friends::class);
    }

    public function RequestCheck($userId, $loggedInUserId){

        $requestQuery = $this
            // De u alias = user en de f/r alias = friends
            ->createQueryBuilder('u')

            ->leftJoin('u.Recipient', 'f')
            ->leftJoin('u.Sender', 'r')
            ->where('f.id = :user_id and r.id = :user_id2 or f.id = :user_id2 and r.id = :user_id')
            ->setParameter('user_id', $userId)
            ->setParameter('user_id2', $loggedInUserId)
            ->getQuery();

        return $requestQuery->getResult();
    }

    public function HomeCheck($loggedInUserId){
        $homeQuery = $this
            // De u alias = user en de f/r alias = friends
            ->createQueryBuilder('u')
            ->leftJoin('u.Recipient', 'f')
            ->leftJoin('u.Sender', 'r')
            ->where('f.id = :user_id and u.AcceptCheck = true or r.id = :user_id and u.AcceptCheck = true')
            ->setParameter('user_id', $loggedInUserId)

            ->getQuery();
        return $homeQuery->getResult();
    }

    // /**
    //  * @return Friends[] Returns an array of Friends objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Friends
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
