<?php

declare(strict_types=1);

namespace App\Shop\PetShop\Product;

class Giraffe extends BaseProduct
{

    public function getName(): string
    {
        // なにかをゴニョるロジック
        throw new \Exception('Not implemented');
    }

    public function getPrice(): int
    {
        // なにかをゴニョるロジック
        throw new \Exception('Not implemented');
    }
}
