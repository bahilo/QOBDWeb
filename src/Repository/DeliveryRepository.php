<?php

namespace App\Repository;

use App\Entity\Bill;
use App\Entity\Delivery;
use App\Entity\QuoteOrder;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Delivery|null find($id, $lockMode = null, $lockVersion = null)
 * @method Delivery|null findOneBy(array $criteria, array $orderBy = null)
 * @method Delivery[]    findAll()
 * @method Delivery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeliveryRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Delivery::class);
    }

    public function findByBillStatus($params)
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.quantityDeliveries', 'q_qt_del')
            ->innerJoin('q_qt_del.OrderDetail', 'q_qt_del_detail')
            ->innerJoin('q_qt_del_detail.QuoteOrder', 'q_qt_del_detail_order')
            ->innerJoin('q.Status', 'q_del_status')
            ->andWhere('q_del_status.Name = :status')
            ->andWhere('q_qt_del_detail_order.id = :orderId')
            ->setParameters(['status'=> $params['status'], 'orderId' => $params['order']->getId() ])
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByOrder($params)
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.quantityDeliveries', 'q_qt_del')
            ->innerJoin('q_qt_del.OrderDetail', 'q_qt_del_q_ord')
            ->innerJoin('q_qt_del.Delivery', 'q_qt_del_del')
            ->innerJoin('q_qt_del_del.Status', 'q_qt_del_del_status')
            ->andWhere('q_qt_del_q_ord.QuoteOrder = :order')
            ->andWhere('q_qt_del_del_status.Name = :status')
            ->setParameters(['status' => $params['status'], 'order' => $params['order']])
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByBill(Bill $bill)
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.quantityDeliveries', 'q_qt_del')
            ->andWhere('q_qt_del.Bill = :bill')
            ->setParameter('bill', $bill)
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByBill(Bill $bill) : ?Delivery
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.quantityDeliveries', 'q_qt_del')
            ->andWhere('q_qt_del.Bill = :bill')
            ->setParameter('bill', $bill)
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return Delivery[] Returns an array of Delivery objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Delivery
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
