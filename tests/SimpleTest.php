<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class SimpleTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    public function testAddition(): void
    {
        $this->assertEquals(2, 1 + 1);
    }

    public function testAdditionFalse(): void
    {
        $this->assertEquals(2, 1 + 1);
    }

    public function testTrue(): void
    {
        $age = 25;
        if ($age > 18) {
            $est_majeur = true;
            $this->assertTrue($est_majeur);
        } else {
            $est_majeur = false;
            $this->assertFalse($est_majeur, "L'utilisateur est mineur");
        }
    }
}
