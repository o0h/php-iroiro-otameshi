<?php

namespace App\Shop;

/**
 * @template TConstraint of ConstraintInterface
 * @template TProduct of ProductInterface
 */
interface CatalogInterface
{
    /**
     * @param array<TConstraint> $constraints
     * @return array<TProduct>
     */
    public function search(array $constraints): array;
}
