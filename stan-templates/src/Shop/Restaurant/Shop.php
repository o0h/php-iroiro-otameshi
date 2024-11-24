<?php

declare(strict_types=1);

namespace App\Shop\Restaurant;

use App\Shop\CatalogInterface;
use App\Shop\ShopInterface;

/**
 * @implements ShopInterface<Catalog>
 */
class Shop implements ShopInterface
{
    private Catalog $catalog;

    public function getProducsts(): array
    {
        $constraints = $this->getConstraintsByContext([]);
        $catalog = $this->catalog->search($constraints);

        return $catalog;
    }

    public function setCatalog($catalog): static
    {
        $this->catalog = $catalog;
        return $this;
    }

    /**
     * @param array<mixed> $context
     * @return array<FoodConstraint>
     */
    private function getConstraintsByContext(array $context): array
    {
        // なにかをゴニョるロジック
        throw new \Exception('Not implemented');

    }
}
