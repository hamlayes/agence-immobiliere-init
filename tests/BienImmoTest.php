<?php

namespace App\Tests;

use App\Entity\BienImmobilier;
use App\Entity\Piece;
use PHPUnit\Framework\TestCase;

class BienImmoTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    public function testSurfaceHabitable(): void
    {
        $bienImmo = new BienImmobilier();

        $piece1 = new Piece();
        $piece1->setSurface(20);
        $piece1->setIsHabitable(true);
        $bienImmo->addPiece($piece1);

        $piece2 = new Piece();
        $piece2->setSurface(30);
        $piece2->setIsHabitable(true);
        $bienImmo->addPiece($piece2);

        $piece3 = new Piece();
        $piece3->setSurface(10);
        $piece3->setIsHabitable(false);
        $bienImmo->addPiece($piece3);

        $this->assertEquals(50, $bienImmo->surfaceHabitable());
    }

    public function testSurfaceNonHabitable(): void
    {
        $bienImmo = new BienImmobilier();

        $piece1 = new Piece();
        $piece1->setSurface(20);
        $piece1->setIsHabitable(true);
        $bienImmo->addPiece($piece1);

        $piece2 = new Piece();
        $piece2->setSurface(30);
        $piece2->setIsHabitable(false);
        $bienImmo->addPiece($piece2);

        $piece3 = new Piece();
        $piece3->setSurface(10);
        $piece3->setIsHabitable(false);
        $bienImmo->addPiece($piece3);

        $this->assertEquals(40, $bienImmo->surfaceNonHabitable());
    }
}
