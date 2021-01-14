<?php

namespace App\Repository;

use App\Entity\Chats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Chats|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chats|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chats[]    findAll()
 * @method Chats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chats::class);
    }

    public function ChatCheck($user1, $user2){
        $chatQuery = $this
            // De u alias = Chat en de f/r alias = Chat users
            ->createQueryBuilder('u')
            ->leftJoin('u.User1', 'f')
            ->leftJoin('u.User2', 'r')
            ->where('f.id = :user_id and r.id = :user2_id or f.id = :user2_id and r.id = :user_id')
            ->setParameter('user_id', $user1)
            ->setParameter('user2_id', $user2)
            ->getQuery();

        return $chatQuery->getResult();
    }


    // /**
    //  * @return Chats[] Returns an array of Chats objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Chats
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
