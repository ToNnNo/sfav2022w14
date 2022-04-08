<?php

namespace App\Tests;

use App\Service\Useless\Calculatrice;
use PHPUnit\Framework\TestCase;

class CalculatriceTest extends TestCase
{
    private $calculatrice;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    public function setUp(): void
    {
        $this->calculatrice = new Calculatrice();
    }

    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    /**
     * @dataProvider providerAddition
     */
    public function testAddition($values, $expected): void
    {
        $result = $this->calculatrice->addition(...$values);
        $this->assertEquals($expected, $result);

        /*$result = $c->addition();
        $this->assertEquals(15, $result);

        $result = $c->addition(1, 2, 3, 4);
        $this->assertEquals(10, $result);

        $result = $c->addition();
        $this->assertEquals(0, $result);*/
    }

    public function testError(): void
    {
        $this->expectException(\Exception::class);
        $this->calculatrice->error();
    }

    public function providerAddition(): array
    {
        return [
            [ [1, 2, 3, 4, 5], 15 ],
            [ [1, 2, 3, 4], 10 ],
            [ [], 0 ],
            [ [1], 1 ],
            [ [1, 0], 1 ],
            [ [0, 1], 1 ],
            [ [-1, 1], 0 ],
            [ [1, -1], 0 ],
            [ [-1, -1], -2 ],
            [ [-2, -1, -3, -4, 5], -5 ],
            [ [0.1, 0.2], 0.3 ],
            [ [1.99, 2.99], 4.98 ],
            [ [-1.99, 2.99], 1 ],
            [ [null, null], 0 ],
            [ [null, 1], 1 ],
            [ [true, true], 0 ],
            [ [true, false], 0 ],
            [ [false, true], 0 ],
            [ [false, null], 0 ],
            [ [true, null], 0 ],
            [ ['1', 1], 1 ],
            [ ['1', "1"], 0 ],
        ];
    }
}
