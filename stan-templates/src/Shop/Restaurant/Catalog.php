<?php

declare(strict_types=1);

namespace App\Shop\Restaurant;

use App\Shop\CatalogInterface;
use App\Shop\Restaurant\Product\Food;

/**
 * @implements CatalogInterface<FoodConstraint, Food>
 */
class Catalog implements CatalogInterface
{
    public function search(array $constraints): array
    {
        // なにかをゴニョるロジック
        throw new \Exception('Not implemented');
    }
}
