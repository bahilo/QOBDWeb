<?php

namespace App\Repository;

use App\Entity\Bill;
use App\Dependency\Utility;
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
    protected $utility;

    public function __construct(ManagerRegistry $registry, Utility $utility)
    {
        parent::__construct($registry, Bill::class);
        $this->utility = $utility;
    }

    /*public function findByOrder($params)
    {
        $bills = [];
        foreach ($this->utility->getOrderDeliveries($params['orderDetails'], $params['status']) as $delivery) {
            if ($delivery) {
                $deliveryStatus = $delivery->getStatus();
                $bill = $delivery->getBill();
                if ($bill && $deliveryStatus && $deliveryStatus->getName() == $params['status'] && !$this->utility->in_array($bills, $bill))
                    array_push($bills, $bill);
            }
        }
        return $bills;
    }*/

    // /**
    //  * @return Bill[] Returns an array of Bill objects
    //  */
    
    public function findByOrder($params)
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.quantityDeliveries', 'q_qt_del')
            ->innerJoin('q_qt_del.Delivery', 'q_qt_del_del')
            ->innerJoin('q_qt_del_del.Status', 'q_qt_del_del_status')
            ->andWhere('q_qt_del_del_status.Name = :val')
            ->setParameter('val', $params['status'])
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
}
