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
 * @method QuantityDelivery|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuantityDelivery|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuantityDelivery[]    findAll()
 * @method QuantityDelivery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuantityDeliveryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuantityDelivery::class);
    }

    // /**
    //  * @return QuantityDelivery[] Returns an array of QuantityDelivery objects
    //  */

    public function findByDelivery(Delivery $delivery)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.Delivery = :delivery')
            ->setParameter('delivery', $delivery)
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByBill(Bill $bill)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.Bill = :bill')
            ->setParameter('bill', $bill)
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByBillStatus(QuoteOrder $order, $deliveryStatus = 'STATUS_NOT_BILLED')
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.OrderDetail', 'q_orddtl')
            ->innerJoin('q_orddtl.QuoteOrder', 'q_q_orddtl_ord')
            ->innerJoin('q.Delivery', 'q_del')
            ->innerJoin('q_del.Status', 'q_del_status')
            ->andWhere('q_del_status.Name = :status')
            ->andWhere('q_q_orddtl_ord.id = :orderId')
            ->setParameters(['status' => $deliveryStatus, "orderId" => $order->getId()])
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult();

        //dump($res); die();
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

    public function findOneByOrderDetailNotBilled(QuoteOrderDetail $orderDetail): ?QuantityDelivery
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.Bill is null')
            ->andWhere('q.OrderDetail = :ord_dtl')
            ->setParameter('ord_dtl', $orderDetail)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByOrderDetailAndBill($params): ?QuantityDelivery
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.OrderDetail = :ord_dtl')
            ->andWhere('q.Bill = :bill')
            ->setParameters(['ord_dtl' => $params['order_detail'], 'bill' => $params['bill']])
            ->getQuery()
            ->getOneOrNullResult();
    }
    /*
    public function findOneBySomeField($value): ?QuantityDelivery
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
