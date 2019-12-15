<?php

namespace App\Repository;

use App\Entity\Agent;
use App\Entity\Discussion;
use App\Entity\Message;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Discussion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Discussion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Discussion[]    findAll()
 * @method Discussion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscussionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Discussion::class);
    }

    // /**
    //  * @return Discussion[] Returns an array of Discussion objects
    //  */
    public function findByAgent(Agent $agent)
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.agentDiscussions', 'd_ad')
            ->innerJoin('d_ad.agent', 'd_ad_a')
            ->andWhere('d_ad_a.id = :id')
            ->setParameter('id', $agent->getId())
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByMessageAgent(Agent $agent, Message $message)
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.agents', 'd_a')
            ->innerJoin('d.messages', 'd_m')
            ->andWhere('d_a.id = :ida')
            ->andWhere('d_m.id = :idm')
            ->setParameters(['ida'=> $agent->getId(), 'idm' => $message->getId()])
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    /*public function findLast(Agent $agent)
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.agents', 'd_a')
            ->innerJoin('d.messages', 'd_m')
            ->andWhere('d_a.id = :ida')
            ->andWhere('d_m.id = :idm')
            ->setParameters(['ida'=> $agent->getId(), 'idm', $message->getId()])
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }*/

    
    public function findLast(Agent $agent): ?Discussion
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.agents', 'd_a')
            ->andWhere('d_a.id = :ida')
            ->setParameters(['ida' => $agent->getId()])
            ->orderBy('d.id', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
}
