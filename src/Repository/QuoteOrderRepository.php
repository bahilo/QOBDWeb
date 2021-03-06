<?php

namespace App\Repository;

use App\Entity\Bill;
use App\Entity\Agent;
use App\Entity\Delivery;
use App\Entity\OrderStatus;
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
    */
    public function findCustomBy($form, Agent $agent)
    {
        $queryBuilder = $this->createQueryBuilder('q');
        $parameters = [];
       
        if (!empty($form['order'])) {
            $queryBuilder->andWhere('q.id = :id');
            $parameters['id'] = $form['order'];
        }

        if (!empty($form['orderStatus'])) {
            $queryBuilder->innerJoin('q.Status', 'q_status');
            $queryBuilder->andWhere('q_status.Name = :status');
            $parameters['status'] = $form['orderStatus'];
        }

        if (!empty($form['bill'])) {
            $queryBuilder->innerJoin('q.quoteOrderDetails', 'q_ord_dtl');
            $queryBuilder->innerJoin('q_ord_dtl.quantityDeliveries', 'q_ord_dtl_qtdel');
            $queryBuilder->innerJoin('q_ord_dtl_qtdel.Bill', 'q_ord_dtl_qtdel_bill');
            $queryBuilder->andWhere('q_ord_dtl_qtdel_bill.id = :billid');
            $parameters['billid'] = $form['bill'];
        }
        if (!empty($form['client'])) {
            $queryBuilder->innerJoin('q.Client', 'q_c');
            $queryBuilder->andWhere('q_c.id = :clientid');
            $parameters['clientid'] = $form['client'];
        }
        if (!empty($form['clientContact'])) {
            $queryBuilder->innerJoin('q.Client', 'q_c');
            $queryBuilder->innerJoin('q_c.contacts', 'q_c_ct');
            $queryBuilder->andWhere('q_c_ct.id = :contactid');
            $parameters['contactid'] = $form['clientContact'];
        }
        if (!empty($form['agent']) && $agent->getIsAdmin()) {
            $queryBuilder->innerJoin('q.Agent', 'q_a');
            $queryBuilder->andWhere('q_a.id = :agentid');
            $parameters['agentid'] = $form['agent'];
        }
        if (!empty($form['dtDebut'])) {
            $queryBuilder->andWhere('q.CreatedAt >= :dtDebut');
            $parameters['dtDebut'] = $form['dtDebut'];
        }
        if (!empty($form['dtFin'])) {
            $queryBuilder->andWhere('q.CreatedAt <= :dtFin');
            $parameters['dtFin'] = $form['dtFin'] . ' 23:59:59';
        }

        if(!$agent->getIsAdmin()){
            $queryBuilder->andWhere('q.Agent = :agent');
            $parameters['agent'] = $agent;
        }

        return $queryBuilder
            ->setParameters($parameters)
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByBill(Bill $bill): ?QuoteOrder
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.quoteOrderDetails', 'q_ord_dtl')
            ->innerJoin('q_ord_dtl.quantityDeliveries', 'q_ord_dtl_qt_del')
            ->andWhere('q_ord_dtl_qt_del.Bill = :bill')
            ->setParameter('bill', $bill)
            ->setMaxResults(1)
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
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function countByStatus(OrderStatus $status): ?int
    {
        $result = $this->createQueryBuilder('q')
            ->andWhere('q.Status = :status')
            ->setParameter('status', $status)
            ->select('count(q)')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        
        return empty($result) ? 0 : reset($result);
    }

    public function findScalarOrderBill(QuoteOrder $order): ?float
    {
        $result = $this->createQueryBuilder('q')
            ->innerJoin('q.quoteOrderDetails', 'q_ord_dtl')
            //->innerJoin('q_ord_dtl.quantityDeliveries', 'q_ord_dtl_qt_del')
            //->andWhere('q_ord_dtl.QuantityDelivery = q_ord_dtl.Quantity')
            ->andWhere('q = :order')
            ->setParameter('order', $order)
            ->groupBy('q.id')
            ->select('SUM(q_ord_dtl.Quantity * q_ord_dtl.ItemSellPrice)')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return empty($result) ? 0 : reset($result);
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
