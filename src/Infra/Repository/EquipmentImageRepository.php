<?php

namespace App\Infra\Repository;

use App\Domain\Entity\EquipmentImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EquipmentImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method EquipmentImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method EquipmentImage[]    findAll()
 * @method EquipmentImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipmentImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EquipmentImage::class);
    }

    // /**
    //  * @return EquipmentImage[] Returns an array of EquipmentImage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EquipmentImage
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
