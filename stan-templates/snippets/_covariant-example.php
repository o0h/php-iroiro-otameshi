<?php
declare(strict_types=1);

/**
 * @template T of Product
 */
interface Catalog
{
    /**
     * @param array<Constraint> $constraints
     * @return iterable<T>
     */
    public function find(array $constraints): iterable;
}

interface Constraint
{
}

interface Product
{
}

/**
 * @template T of Product
 */
abstract class CatalogFactory
{
    /**
     * @param array<T> $products
     * @return Catalog<T>
     */
    public static function create(array $products): Catalog
    {
        throw new \Exception('Not implemented');
    }
}

/**
 * @extends CatalogFactory<DigitalProduct>
 */
class DigitalProductCatalogFactory extends CatalogFactory
{
    public static function create(array $products): Catalog
    {
        // ごにょ
        return parent::create($products);
    }
}

class DigitalProduct implements Product
{
}

class DigitalProductCatalog implements Catalog
{
    public function find(array $constraints): iterable
    {
        return [];
    }
}

class PhysicalProduct
{
}
