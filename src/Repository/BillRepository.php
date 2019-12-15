<?php

namespace App\Repository;

use App\Entity\Bill;
use App\Entity\Delivery;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Bill|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bill|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bill[]    findAll()
 * @method Bill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BillRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bill::class);
    }

    // /**
    //  * @return Bill[] Returns an array of Bill objects
    //  */
    
    public function findByOrder($params)
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.quantityDeliveries', 'q_qt_del')
            ->innerJoin('q_qt_del.OrderDetail', 'q_qt_del_q_ord')
            ->innerJoin('q_qt_del.Delivery', 'q_qt_del_del')
            ->innerJoin('q_qt_del_del.Status', 'q_qt_del_del_status')
            ->andWhere('q_qt_del_q_ord.QuoteOrder = :order')
            ->andWhere('q_qt_del_del_status.Name = :status')
            ->setParameters(['status'=> $params['status'], 'order' => $params['order']])
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult();
        ;
    }


    /*
    public function findOneBySomeField($value): ?Bill
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findOneByDelivery(Delivery $delivery): ?Bill
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.quantityDeliveries', 'q_qt_del')
            ->andWhere('q_qt_del.Delivery = :delivery')
            ->setParameter('delivery', $delivery)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
