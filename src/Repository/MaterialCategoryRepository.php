<?php

namespace App\Repository;

use App\Entity\Material\MaterialCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MaterialCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterialCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterialCategory[]    findAll()
 * @method MaterialCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaterialCategory::class);
    }

    // /**
    //  * @return MaterialCategory[] Returns an array of MaterialCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MaterialCategory
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
