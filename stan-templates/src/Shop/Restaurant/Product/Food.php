<?php

declare(strict_types=1);

namespace App\Shop\Restaurant\Product;

class Food extends BaseProduct
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
