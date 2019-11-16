<?php

namespace App\Repository;

use App\Entity\QuoteOrderDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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

    public function findByBill($deliveryStatus = 'STATUS_NOT_BILLED')
    {
        return $this->createQueryBuilder('q')
            ->leftJoin('q.Delivery', 'q_del')
            ->leftJoin('q_del.Status','q_del_status')
            ->andWhere('q_del_status.Name = :val')
            ->setParameter('val', $deliveryStatus)
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
