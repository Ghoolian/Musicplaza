<?php

namespace App\Repository\Authentication;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function withPermissions($userId)
    {
        $permissionQuery = $this
            ->createQueryBuilder('u')
            ->leftJoin('u.clusters', 'g')
            ->leftJoin('g.permissions', 'p')
            ->where('u.id = :user_id')
            ->select('p.name')
            ->setParameter('user_id', $userId)
            ->getQuery();

        return $permissionQuery->getResult();
    }

    public function findUsersBySearch(string $input)
    {
        // "N" is een alias die wordt gebruikt voor de rest van de query.
        return $this->createQueryBuilder('n')
            ->where('n.name LIKE :input')
            ->setParameter(':input', '%'.$input.'%')
            ->orderBy('n.name', 'ASC')
            ->getQuery()
            ->getResult();

    }



    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
