<?php

namespace App\Repository;

use App\Entity\ActionTracker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ActionTracker|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActionTracker|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActionTracker[]    findAll()
 * @method ActionTracker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionTrackerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActionTracker::class);
    }

    // /**
    //  * @return ActionTracker[] Returns an array of ActionTracker objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ActionTracker
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
