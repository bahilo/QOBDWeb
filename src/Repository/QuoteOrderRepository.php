<?php

namespace App\Repository;

use App\Entity\Bill;
use App\Entity\Delivery;
use App\Entity\QuoteOrder;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

    public function findOneByBill(Bill $bill): ?QuoteOrder
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.quoteOrderDetails', 'q_ord_dtl')
            ->innerJoin('q_ord_dtl.quantityDeliveries', 'q_ord_dtl_qt_del')
            ->andWhere('q_ord_dtl_qt_del.Bill = :bill')
            ->setParameter('bill', $bill)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByDelivery(Delivery $delivery): ?QuoteOrder
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.quoteOrderDetails', 'q_ord_dtl')
            ->innerJoin('q_ord_dtl.quantityDeliveries', 'q_ord_dtl_qt_del')
            ->andWhere('q_ord_dtl_qt_del.Delivery = :delivery')
            ->setParameter('delivery', $delivery)
            ->getQuery()
            ->getOneOrNullResult();
    }

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
