<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../Calculator.php';

class CalculatorTest extends TestCase
{
    private $calculator;

    protected function setUp(): void
    {
        $this->calculator = new Calculator();
    }

    public function testAdd()
    {
        $this->assertEquals(4, $this->calculator->add(2, 2));
        $this->assertEquals(0, $this->calculator->add(-2, 2));
    }

    public function testSubtract()
    {
        $this->assertEquals(0, $this->calculator->subtract(2, 2));
        $this->assertEquals(-4, $this->calculator->subtract(-2, 2));
    }

    public function testMultiply()
    {
        $this->assertEquals(4, $this->calculator->multiply(2, 2));
        $this->assertEquals(-4, $this->calculator->multiply(-2, 2));
    }

    public function testDivide()
    {
        $this->assertEquals(2, $this->calculator->divide(4, 2));
        $this->assertEquals(-1, $this->calculator->divide(-2, 2));
    }

    public function testDivideByZero()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->calculator->divide(4, 0);
    }
}
