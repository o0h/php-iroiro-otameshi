<?php
declare(strict_types=1);

namespace App\Shop;

/**
 * @template T of CatalogInterface
 */
interface ShopInterface
{
    /**
     * @param T $catalog
     */
    public function setCatalog($catalog): static;

    /**
     * @return T
     */
    public function getCatalog(): CatalogInterface;
}
