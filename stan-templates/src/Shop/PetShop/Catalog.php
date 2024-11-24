<?php

namespace App\Shop\PetShop;

use App\Shop\CatalogInterface;
use App\Shop\PetShop\Product\BaseProduct;
use App\Shop\PetShop\Product\Giraffe;
use App\Shop\ProductInterface;

/**
 * @implements CatalogInterface<BaseProduct>
 */
class Catalog implements CatalogInterface
{
    public function addProduct(ProductInterface $product): static
    {
        // なにかをゴニョるロジック
        throw new \Exception('Not implemented');
    }

    public function getRandomOne(): ProductInterface
    {
        // なにかをゴニョるロジック
        throw new \Exception('Not implemented');
    }
}
