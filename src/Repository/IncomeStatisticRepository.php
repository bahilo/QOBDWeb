<?php

namespace App\Repository;

use App\Entity\IncomeStatistic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method IncomeStatistic|null find($id, $lockMode = null, $lockVersion = null)
 * @method IncomeStatistic|null findOneBy(array $criteria, array $orderBy = null)
 * @method IncomeStatistic[]    findAll()
 * @method IncomeStatistic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncomeStatisticRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IncomeStatistic::class);
    }

    // /**
    //  * @return IncomeStatistic[] Returns an array of IncomeStatistic objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IncomeStatistic
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
