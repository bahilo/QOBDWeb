<?php

namespace App\Repository;

use App\Entity\Bill;
use App\Entity\Delivery;
use App\Entity\QuoteOrder;
use App\Entity\QuantityDelivery;
use App\Entity\QuoteOrderDetail;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method QuoteOrderDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuoteOrderDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuoteOrderDetail[]    findAll()
 * @method QuoteOrderDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuoteOrderDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuoteOrderDetail::class);
    }

    // /**
    //  * @return QuoteOrderDetail[] Returns an array of QuoteOrderDetail objects
    //  */

    public function findByQuantityRecieved(QuoteOrder $order, int $val = 0)
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.QuoteOrder', 'q_ord')
            ->andWhere('q.QuantityRecieved > :val')
            ->andWhere('q_ord.id = :orderId')
            ->setParameters(['val' => $val, "orderId" => $order->getId()])
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByBillStatus(QuoteOrder $order, $deliveryStatus = 'STATUS_NOT_BILLED')
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.QuoteOrder', 'q_ord')
            ->innerJoin('q.quantityDeliveries', 'q_qt_del')
            ->innerJoin('q_qt_del.Delivery', 'q_del')
            ->innerJoin('q_del.Status','q_del_status')
            ->andWhere('q_del_status.Name = :status')
            ->andWhere('q_ord.id = :orderId')
            ->setParameters(['status'=> $deliveryStatus, "orderId" => $order->getId()])
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult();

        //dump($res); die();
    }

    public function findByBill(Bill $bill)
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.quantityDeliveries', 'q_qt_del')
            ->andWhere('q_qt_del.Bill = :bill')
            ->setParameters(['bill' => $bill])
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByDelivery(QuoteOrder $order, Delivery $delivery)
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.QuoteOrder', 'q_ord')
            ->innerJoin('q.quantityDeliveries', 'q_qt_del')
            ->innerJoin('q_qt_del.Delivery', 'q_qt_del_del')
            ->andWhere('q_qt_del_del.id = :val')
            ->andWhere('q_ord.id = :orderId')
            ->setParameters(['val' => $delivery->getId(), "orderId" => $order->getId()])
            ->getQuery()
            ->getResult();
    }

    public function findByOrder(QuoteOrder $order)
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.QuoteOrder', 'q_ord')
            ->innerJoin('q.quantityDeliveries', 'q_qt_del')
            ->innerJoin('q_qt_del.Delivery', 'q_qt_del_del')
            ->andWhere('q_ord.id = :orderId')
            ->setParameters([ "orderId" => $order->getId()])
            ->getQuery()
            ->getResult();
    }

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

    public function findOneByQuantityDelivery(QuoteOrder $order, QuantityDelivery $qtDelivery): ?QuoteOrderDetail
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.QuoteOrder', 'q_ord')
            ->innerJoin('q.quantityDeliveries', 'q_qt_del')
            ->andWhere('q_qt_del.id = :val')
            ->andWhere('q_ord.id = :orderId')
            ->setParameters(['val'=> $qtDelivery->getId(), "orderId" => $order->getId()])
            ->getQuery()
            ->getOneOrNullResult();
    }

    /*
    public function findOneBySomeField($value): ?QuoteOrderDetail
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
