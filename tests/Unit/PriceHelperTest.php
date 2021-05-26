<?php

namespace Tests\Unit;

use App\Classes\PriceHelper;
use PHPUnit\Framework\TestCase;

class PriceHelperTest extends TestCase
{
    private array $priceTier;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->priceTier = [
            0 => 1.5, // 0 - 10,000 qty => $1.5
            10001 => 1, // 10,000 - 100,000 qty => $1
            100001 => 0.5, // 100,001 & more => $0.5
        ];
    }

    /**
     * @dataProvider unitPriceTier
     */
    public function testUnitPriceTier($qty, $expected): void
    {
        self::assertSame($expected, PriceHelper::getUnitPriceTierAtQty($qty, $this->priceTier));
    }

    /**
     * @dataProvider totalPriceTier
     */
    public function testTotalPriceTier($qty, $expected): void
    {
        self::assertSame($expected, PriceHelper::getTotalPriceTierAtQty($qty, $this->priceTier));
    }

    /**
     * @dataProvider qtyAndPrice
     */
    public function testPriceAtEachQty($qty, $expected): void
    {
        self::assertSame($expected, PriceHelper::getPriceAtEachQty($qty, $this->priceTier, false));
    }

    /**
     * @dataProvider qtyAndPriceCumulative
     */
    public function testPriceAtEachQtyCumulative($qty, $expected): void
    {
        self::assertSame($expected, PriceHelper::getPriceAtEachQty($qty, $this->priceTier, true));
    }

    public function unitPriceTier(): array
    {
        return [
            [0, 0.0],
            [1, 1.5],
            [10000, 1.5],
            [10001, 1.0],
            [100000, 1.0],
            [100001, 0.5],
        ];
    }

    public function totalPriceTier(): array
    {
        return [
            [0, 0.0],
            [1, 1.5],
            [10000, 15000.0],
            [10001, 15001.0],
            [100000, 105000.0],
            [100001, 105000.5],
        ];
    }

    public function qtyAndPrice(): array
    {
        return [
            [
                [
                    933,
                    22012,
                    24791,
                    15553,
                ],
                [
                    1399.5,
                    27012.0,
                    29791.0,
                    20553.0,
                ],
            ],
        ];
    }

    public function qtyAndPriceCumulative(): array
    {
        return [
            [
                [
                    933,
                    22012,
                    24791,
                    15553,
                ],
                [
                    1399.5,
                    26545.5,
                    24791.0,
                    15553.0,
                ],
            ],
        ];
    }
}

class PriceHelperTest1 extends TestCase
{
    private array $priceTier1;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->priceTier1 = [
            0 => 1.5,
            5001 => 1,
            60001 => 0.5,
            70001 => 0.4
        ];
    }

    /**
     * @dataProvider unitPriceTier
     */
    public function testUnitPriceTier($qty, $expected): void
    {
        self::assertSame($expected, PriceHelper::getUnitPriceTierAtQty($qty, $this->priceTier1));
    }

    /**
     * @dataProvider totalPriceTier
     */
    public function testTotalPriceTier($qty, $expected): void
    {
        self::assertSame($expected, PriceHelper::getTotalPriceTierAtQty($qty, $this->priceTier1));
    }

    /**
     * @dataProvider qtyAndPrice
     */
    public function testPriceAtEachQty($qty, $expected): void
    {
        self::assertSame($expected, PriceHelper::getPriceAtEachQty($qty, $this->priceTier1, false));
    }

    /**
     * @dataProvider qtyAndPriceCumulative
     */
    public function testPriceAtEachQtyCumulative($qty, $expected): void
    {
        self::assertSame($expected, PriceHelper::getPriceAtEachQty($qty, $this->priceTier1, true));
    }


    public function unitPriceTier(): array
    {
        return [
            [0, 0.0],
            [1, 1.5],
            [5000, 1.5],
            [5001, 1.0],
            [60000, 1.0],
            [60001, 0.5],
            [70000, 0.5],
            [70001, 0.4],
        ];
    }

    public function totalPriceTier(): array
    {
        return [
            [0, 0.0],
            [1, 1.5],
            [5000, 7500.0],
            [60001, 62500.5],
            [70000, 67500.0],
            [70001, 67500.4],
        ];
    }

    public function qtyAndPrice(): array
    {
        return [
            [
                [
                    933,
                    22012,
                    24791,
                    15553,
                ],
                [
                    1399.5,
                    24512.0,
                    27291.0,
                    18053.0,
                ],
            ],
        ];
    }

    public function qtyAndPriceCumulative(): array
    {
        return [
            [
                [
                    933,
                    22012,
                    24791,
                    15553,
                ],
                [
                    1399.5,
                    24045.5,
                    24791.0,
                    13908.5,
                ],
            ],
        ];
    }
}