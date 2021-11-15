<?php

namespace Tests\Unit;

use App\Classes\PriceHelper;
use PHPUnit\Framework\TestCase;

class PriceHelperTest extends TestCase {

    private array $priceTier;

    public function __construct(?string $name = null, array $data = [], $dataName = '') {
        parent::__construct($name, $data, $dataName);

        $this->priceTier = [
            0      => 1.5,
            // 0 - 10,000 qty => $1.5
            10001  => 1,
            // 10,000 - 100,000 qty => $1
            100001 => 0.5,
            // 100,001 & more => $0.5
        ];
    }

    /**
     * @dataProvider unitPriceTier
     */
    public function testUnitPriceTier($qty, $expected) : void {
        $this->assertSame($expected, PriceHelper::getUnitPriceTierAtQty($qty, $this->priceTier));
    }

    /**
     * @dataProvider totalPriceTier
     */
    public function testTotalPriceTier($qty, $expected) : void {
        $this->assertSame($expected, PriceHelper::getTotalPriceTierAtQty($qty, $this->priceTier));
    }

    /**
     * @dataProvider qtyAndPrice
     */
    public function testPriceAtEachQty($qty, $expected) : void {
        $this->assertSame($expected, PriceHelper::getPriceAtEachQty($qty, $this->priceTier, false));
    }

    /**
     * @dataProvider qtyAndPriceCumulative
     */
    public function testPriceAtEachQtyCumulative($qty, $expected) : void {
        $this->assertSame($expected, PriceHelper::getPriceAtEachQty($qty, $this->priceTier, true));
    }

    public function unitPriceTier() : array {
        return [
            [0, 0.0],
            [1, 1.5],
            [10000, 1.5],
            [10001, 1.0],
            [100000, 1.0],
            [100001, 0.5],
        ];
    }

    public function totalPriceTier() : array {
        return [
            [0, 0.0],
            [1, 1.5],
            [10000, 15000.0],
            [10001, 15001.0],
            [100000, 105000.0],
            [100001, 105000.5],
        ];
    }

    public function qtyAndPrice() : array {
        return [
            [
                [1000000],
                [555000.0]
            ],
            [
                [100000],
                [105000.0]
            ],
            [
                [10000],
                [15000.0]
            ],
            [
                [10000, 100000, 1000000],
                [
                    15000.0,
                    105000.0,
                    555000.0,
                ]
            ],
            [
                [10000, 10001, 100000, 100001, 1000000, 1000001],
                [
                    15000.0,
                    15001.0,
                    105000.0,
                    105000.5,
                    555000.0,
                    555000.5,
                ]
            ],
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

    public function qtyAndPriceCumulative() : array {
        return [
            [
                [1000000],
                [555000.0]
            ],
            [
                [100000],
                [105000.0]
            ],
            [
                [10000],
                [15000.0]
            ],
            [
                [10000, 100000, 1000000],
                [
                    15000.0,
                    95000.0,
                    500000.0,
                ]
            ],
            [
                [10000, 10001, 100000, 100001, 1000000, 1000001],
                [
                    15000.0,
                    10001.0,
                    89999.5,
                    50000.5,
                    500000.0,
                    500000.5,
                ]
            ],
            [
                [
                    933,
                    22012,
                    24791,
                    15553,
                    69827,
                    24791,
                    100000,
                    1234512
                ],
                [
                    1399.5,
                    26545.5,
                    24791.0,
                    15553.0,
                    53269.0,
                    12395.5,
                    50000.0,
                    617256.0
                ],
            ],

        ];
    }

}
