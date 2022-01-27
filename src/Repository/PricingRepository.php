<?php

namespace App\Repository;

use App\Entity\Material\Material;
use App\Entity\Material\Pricing;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pricing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pricing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pricing[]    findAll()
 * @method Pricing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PricingRepository extends ServiceEntityRepository
{
    use StateFullRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pricing::class);
    }

    /**
     * @return array<int, Pricing>
     */
    public function findForMaterialAndUser(Material $material, User $user): array
    {
        return $this->withMaterial($material)->withUser($user)->run();
    }

    protected function withMaterial(Material $material): self
    {
        $this->getInstance('pricing')
            ->leftJoin('pricing.material', 'material')
            ->addSelect('material')
            ->where('material.id = :materialId')
            ->setParameter('materialId', $material->getId())
            ->orderBy('pricing.value', 'ASC')
        ;

        return $this;
    }

    protected function withUser(User $user): self
    {
        $this->getInstance('pricing')
            ->leftJoin('pricing.circles', 'circle')
            ->leftJoin('circle.members', 'user')
            ->addSelect('circle')
            ->addSelect('user')
            ->andWhere('user.id = :userId')
            ->setParameter('userId', $user->getId())
        ;

        return $this;
    }
}
