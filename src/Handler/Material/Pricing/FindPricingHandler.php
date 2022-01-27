<?php

declare(strict_types=1);

namespace App\Handler\Material\Pricing;

use App\Entity\Material\Material;
use App\Entity\Material\Pricing;
use App\Entity\User;
use App\Repository\PricingRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class FindPricingHandler implements MessageHandlerInterface
{
    public function __construct(
        private PricingRepository $pricingRepository,
    ) {}

    public function __invoke(Material $material, User $user): ?Pricing
    {
        $pricings = $this->pricingRepository->findForMaterialAndUser($material, $user);
        $bestPrice = null;
        foreach ($pricings as $pricing) {
            if (!$bestPrice || $bestPrice->getValue() > $pricing->getValue()) {
                $bestPrice = $pricing;
            }
            if ($bestPrice->getValue() === 0.0) {
                break;
            }
        }

        return $bestPrice;
    }
}