<?php

declare(strict_types=1);

namespace App\Shop;

interface ProductInterface
{
    public function getName(): string;
    public function getPrice(): int;
}
