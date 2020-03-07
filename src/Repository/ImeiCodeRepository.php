<?php

namespace App\Repository;

use App\Entity\ImeiCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ImeiCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImeiCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImeiCode[]    findAll()
 * @method ImeiCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImeiCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImeiCode::class);
    }

    // /**
    //  * @return ImeiCode[] Returns an array of ImeiCode objects
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
    public function findOneBySomeField($value): ?ImeiCode
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
