<?php

namespace App\Repository;

use App\Entity\Posts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Posts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Posts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Posts[]    findAll()
 * @method Posts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posts::class);
    }

    public function PostCheck($loggedInUserId){
        $postQuery = $this
            // De p en de c alias = posts
            ->createQueryBuilder('p')
            ->leftJoin('p.User', 'c')
            ->where('c.id != :user_id')
            ->setParameter('user_id', $loggedInUserId)
            ->getQuery();
        return $postQuery->getResult();
    }

    public function findPostsBySearch(string $input)
    {
        // "N" is een alias die wordt gebruikt voor de rest van de query.
        return $this->createQueryBuilder('n')
            ->where('n.Text LIKE :input')
            ->setParameter(':input', '%'.$input.'%')
            ->orderBy('n.Text', 'ASC')
            ->getQuery()
            ->getResult();

    }

    // /**
    //  * @return Posts[] Returns an array of Posts objects
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
    public function findOneBySomeField($value): ?Posts
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
