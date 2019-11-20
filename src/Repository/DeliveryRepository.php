<?php

namespace App\Repository;

use App\Entity\Delivery;
use App\Entity\QuoteOrder;
use App\Dependency\Utility;
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
    protected $utility;

    public function __construct(ManagerRegistry $registry, Utility $utility)
    {
        parent::__construct($registry, Delivery::class);
        $this->utility = $utility;
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

    /*public function findByOrder()
    {
        $deliveries = [];
        foreach ($params['orderDetails'] as $detail) {
            foreach ($detail->getDelivery() as $delivery) {
                if ($delivery) {
                    $deliveryStatus = $delivery->getStatus();
                    if ($deliveryStatus && $deliveryStatus->getName() == $params['status'] && !$this->utility->in_array($deliveries, $delivery))
                        array_push($deliveries, $delivery);
                }
            }
        }
        return $deliveries;
    }*/

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
