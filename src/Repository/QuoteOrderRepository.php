<?php

namespace App\Repository;

use App\Entity\QuoteOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method QuoteOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuoteOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuoteOrder[]    findAll()
 * @method QuoteOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuoteOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuoteOrder::class);
    }

    // /**
    //  * @return QuoteOrder[] Returns an array of QuoteOrder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuoteOrder
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
