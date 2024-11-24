<?php

declare(strict_types=1);

namespace App;

interface MeshiInterface{};

/**
 * @template T of MeshiInterface
 */
class UmaiMeshi
{
    /**
     * @param T $meshi
     */
    private function __construct(private readonly UmaiMeshi $meshi)
    {
    }

    /**
     * @param class-string<T> $meshiName
     * @return T
     */
    public static function chouri(string $meshiName): static
    {
        return new static($meshiName::create());
    }

    /**
     * @return class-string<T>
     */
    public function kakunin(): string
    {
        return $this->meshi::class;
    }
}
