<?php

namespace App\Repository;

use App\Entity\Material\MaterialImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MaterialImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterialImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterialImage[]    findAll()
 * @method MaterialImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaterialImage::class);
    }
}
