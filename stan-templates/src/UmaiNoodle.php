<?php

declare(strict_types=1);


namespace App;

interface NoodleInterface extends MeshiInterface {}

/**
 * @extends UmaiMeshi<NoodleInterface>
 */
class UmaiNoodle extends UmaiMeshi
{
    public function test(): void
    {
        \PHPStan\dumpType($this->kakunin());
    }

}
