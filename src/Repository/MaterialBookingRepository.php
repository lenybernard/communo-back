<?php

namespace App\Repository;

use App\Entity\Material\Booking\MaterialBooking;
use App\Entity\Material\Material;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MaterialBooking|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterialBooking|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterialBooking[]    findAll()
 * @method MaterialBooking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialBookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaterialBooking::class);
    }

    /**
     * @return array<int, MaterialBooking>
     */
    public function findByMaterial(Material $material): array
    {
        return $this->createQueryBuilder('mb')
            ->join('mb.material', 'material')
            ->andWhere('material = :materialId')
            ->setParameter('val', $material->getId())
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<int, MaterialBooking>
     */
    public function findByMaterialBetweenDates(string $materialId, \DateTime $startDate, \DateTime $endDate): array
    {
        $startDateStr = $startDate->format('Y-m-d H:i:s');
        $endDateStr = $endDate->format('Y-m-d H:i:s');
        return $this->getEntityManager()->getConnection()->fetchAllAssociative(
            "SELECT mb.*
                FROM material_booking mb
                WHERE mb.material_id = '${materialId}'
                AND (mb.start_date, mb.end_date) OVERLAPS (DATE '${startDateStr}', DATE '${endDateStr}')
                AND mb.status != 'estimated'
                "
        );
    }

    public function countAvailabilitiesBetweenDates(string $materialId, \DateTime $startDate, \DateTime $endDate): int
    {
        $startDateStr = $startDate->format('Y-m-d');
        $endDateStr = $endDate->format('Y-m-d');
        return $this->getEntityManager()->getConnection()->fetchOne(
            "SELECT count(mb.*)
                FROM material_booking mb
                WHERE mb.material_id = '${materialId}'
                AND daterange(mb.start_date, mb.end_date, '[]') && daterange('${startDateStr}', '${endDateStr}', '[]')
                "
        );
    }
}
