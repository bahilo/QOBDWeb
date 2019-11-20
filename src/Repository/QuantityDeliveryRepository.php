<?php

namespace App\Repository;

use App\Entity\Delivery;
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
            ->leftJoin('q.Delivery', 'q_del')
            ->andWhere('q_del.id = :val')
            ->setParameter('val', $delivery->getId())
            ->orderBy('q.id', 'ASC')
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

    public function findOneByOrderDetail(QuoteOrderDetail $orderDetail): ?QuantityDelivery
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.OrderDetail', 'q_od')
            ->andWhere('q_od.id = :val')
            ->setParameter('val', $orderDetail->getId())
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
