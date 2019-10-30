<?php

namespace App\Repository;

use App\Entity\ItemGroupe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ItemGroupe|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemGroupe|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemGroupe[]    findAll()
 * @method ItemGroupe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemGroupeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemGroupe::class);
    }

    // /**
    //  * @return ItemGroupe[] Returns an array of ItemGroupe objects
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
    public function findOneBySomeField($value): ?ItemGroupe
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
