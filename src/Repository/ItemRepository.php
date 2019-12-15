<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\QuoteOrder;
use App\Entity\QuoteOrderDetail;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    // /**
    //  * @return Item[] Returns an array of Item objects
    //  */
    
    public function findByOrder(QuoteOrder $order)
    {
        return $this->createQueryBuilder('i')
            ->innerJoin('i.quoteOrderDetails', 'q_ord_dtl')
            ->andWhere('q_ord_dtl.QuoteOrder= :ord')
            ->setParameter('ord', $order)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findOneByOrderDetail(QuoteOrderDetail $orderDetail): ?Item
    {
        return $this->createQueryBuilder('i')
            ->innerJoin('i.quoteOrderDetails', 'q_ord_dtl')
            ->andWhere('q_ord_dtl.id = :ord_dtl')
            ->setParameter('ord_dtl', $orderDetail->getId())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    /*
    public function findOneBySomeField($value): ?Item
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
