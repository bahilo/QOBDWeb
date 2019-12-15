<?php

namespace App\Repository;

use App\Entity\AgentDiscussion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AgentDiscussion|null find($id, $lockMode = null, $lockVersion = null)
 * @method AgentDiscussion|null findOneBy(array $criteria, array $orderBy = null)
 * @method AgentDiscussion[]    findAll()
 * @method AgentDiscussion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgentDiscussionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgentDiscussion::class);
    }

    // /**
    //  * @return AgentDiscussion[] Returns an array of AgentDiscussion objects
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
    public function findOneBySomeField($value): ?AgentDiscussion
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
