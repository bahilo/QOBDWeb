<?php

namespace App\Repository;

use App\Entity\ActionRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ActionRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActionRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActionRole[]    findAll()
 * @method ActionRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionRoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActionRole::class);
    }

    // /**
    //  * @return ActionRole[] Returns an array of ActionRole objects
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
    public function findOneBySomeField($value): ?ActionRole
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
