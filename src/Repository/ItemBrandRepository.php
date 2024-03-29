<?php

namespace App\Repository;

use App\Entity\ItemBrand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ItemBrand|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemBrand|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemBrand[]    findAll()
 * @method ItemBrand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemBrandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemBrand::class);
    }

    // /**
    //  * @return ItemBrand[] Returns an array of ItemBrand objects
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
    public function findOneBySomeField($value): ?ItemBrand
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
