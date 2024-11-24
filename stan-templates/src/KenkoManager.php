<?php

declare(strict_types=1);


namespace App\Menu;


interface Meshi{}
abstract class Donburi implements Meshi{}
abstract class Men implements Meshi{}

/** @template T of Meshi */
interface Kondate{}
/** @implements Kondate<Men> */
class MenTeishoku implements Kondate{}
/** @implements Kondate<Donburi> */
class DonburiSet implements Kondate{}

class MenuManager
{
    /**
     * @param Kondate<covariant Meshi> $kondate
     */
    public function getFukusais(Kondate $kondate): array // @phpstan-ignore missingType.iterableValue
    {
        // nanika suru
        return [];
    }

}

$manager = new MenuManager();
$donburiSet = new DonburiSet();
$fukusais = $manager->getFukusais($donburiSet);
