<?php

namespace App\Repository;

use App\Entity\BlogSetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BlogSetting|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogSetting|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogSetting[]    findAll()
 * @method BlogSetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogSettingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogSetting::class);
    }

    // /**
    //  * @return BlogSetting[] Returns an array of BlogSetting objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BlogSetting
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
