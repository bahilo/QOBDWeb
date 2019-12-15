<?php

namespace App\Repository;

use App\Entity\Delivery;
use App\Entity\DeliveryStatus;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method DeliveryStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeliveryStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeliveryStatus[]    findAll()
 * @method DeliveryStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeliveryStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeliveryStatus::class);
    }

    // /**
    //  * @return DeliveryStatus[] Returns an array of DeliveryStatus objects
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

    public function findOneByDelivery(Delivery $delivery): ?DeliveryStatus
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.deliveries','d_dl')
            ->andWhere('d_dl.id = :deliveryId')
            ->setParameter('deliveryId', $delivery->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }
    /*
    public function findOneBySomeField($value): ?DeliveryStatus
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
