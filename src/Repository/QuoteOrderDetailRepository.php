<?php

namespace App\Repository;

use App\Entity\Bill;
use App\Entity\Delivery;
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

    public function findByQuantityRecieved(int $val = 0)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.QuantityRecieved > :val')
            ->setParameter('val', $val)
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByBillStatus($deliveryStatus = 'STATUS_NOT_BILLED')
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.quantityDeliveries', 'q_qt_del')
            ->innerJoin('q_qt_del.Delivery', 'q_del')
            ->innerJoin('q_del.Status','q_del_status')
            ->andWhere('q_del_status.Name = :val')
            ->setParameter('val', $deliveryStatus)
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
            ->setParameter('bill', $bill)
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByDelivery(Delivery $delivery)
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.quantityDeliveries', 'q_qt_del')
            ->innerJoin('q_qt_del.Delivery', 'q_qt_del_del')
            ->andWhere('q_qt_del_del.id = :val')
            ->setParameter('val', $delivery->getId())
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

    public function findOneByQuantityDelivery(QuantityDelivery $qtDelivery): ?QuoteOrderDetail
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.quantityDeliveries', 'q_qt_del')
            ->andWhere('q_qt_del.id = :val')
            ->setParameter('val', $qtDelivery->getId())
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
