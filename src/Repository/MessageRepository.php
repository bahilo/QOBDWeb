<?php

namespace App\Repository;

use App\Entity\Agent;
use App\Entity\Discussion;
use App\Entity\Message;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    // /**
    //  * @return Message[] Returns an array of Message objects
    //  */
    
    public function findByDiscussion(Discussion $discussion)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.Discussion = :discussion')
            ->setParameters(['discussion' => $discussion])
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
    public function findUnReadByAgent(Agent $agent)
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.Agent', 'd_a')
            ->andWhere('d.IsRed = :read')
            ->andWhere('d_a.id = :id')
            ->setParameters(['id'=> $agent->getId(), 'read' => false])
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
    

    /*
    public function findOneBySomeField($value): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
