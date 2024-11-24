<?php

declare(strict_types=1);

namespace App\Shop\PetShop;

use App\Shop\CatalogInterface;
use App\Shop\Restaurant\Catalog;
use App\Shop\ShopInterface;

/**
 * @implements ShopInterface<Catalog>
 */
class Shop implements ShopInterface
{

    public function setCatalog($catalog): static
    {
        // なにかをゴニョるロジック
        throw new \Exception('Not implemented');
    }

    public function getCatalog(): CatalogInterface
    {
        // なにかをゴニョるロジック
        throw new \Exception('Not implemented');
    }
}
