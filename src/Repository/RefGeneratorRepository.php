<?php

namespace App\Repository;

use App\Entity\RefGenerator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method RefGenerator|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefGenerator|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefGenerator[]    findAll()
 * @method RefGenerator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefGeneratorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefGenerator::class);
    }

    // /**
    //  * @return RefGenerator[] Returns an array of RefGenerator objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RefGenerator
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
