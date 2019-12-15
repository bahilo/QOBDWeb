<?php

namespace App\Repository;

use App\Entity\Agent;
use App\Entity\Discussion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Agent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Agent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Agent[]    findAll()
 * @method Agent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agent::class);
    }

    // /**
    //  * @return Agent[] Returns an array of Agent objects
    //  */
    
    public function findByDiscussion(Discussion $discussion)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.agentDiscussions', 'a_ad')
            ->innerJoin('a_ad.discussion', 'a_ad_d')
            ->andWhere('a_ad_d.id = :id')
            ->setParameter('id', $discussion->getId())
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Agent
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
