<?php

namespace App\Classes;

class PriceHelper
{
    /*
     * Todo: Coding Test for Technical Hires
     * Please read the instructions on the README.md
     * Your task is to write the functions for the PriceHelper class
     * A set of sample test cases and expected results can be found in PriceHelperTest
     */

    /**
     * Task: Write a function to return the unit price of an item based on the quantity
     *
     * Question:
     * If I purchase 10,000 bicycles, the unit price of the 10,000th bicycle would be 1.50
     * If I purchase 10,001 bicycles, the unit price of the 10,001st bicycle would be 1.00
     * If I purchase 100,001 bicycles, what would be the unit price of the 100,001st bicycle?
     *
     * @param int $qty
     * @param array $tiers
     * @return float
     */
    public static function getUnitPriceTierAtQty(int $qty, array $tiers): float
    {
        // in php, semi-colon are NECESSARY to mark end of statement. in this case at end of 'return'. this is unlike JS.
        // based on priceTier, which is an array with comma-separated key => value pairs that can be accessed using square bracket notation.
        if ($qty == 0) {
            return 0.0;
        } elseif ($qty <= 10000) {
            return $tiers[0];
        } elseif ($qty <= 100000) {
            return $tiers[10001];
        } else {
            return $tiers[100001];
        }
    }

    /**
     * Task: Write a function to return the total price based on the quantity
     *
     * Question:
     * If I purchase 10,000 bicycles, the total price would be 1.5 * 10,000 = $15,000
     * If I purchase 10,001 bicycles, the total price would be (1.5 * 10,000) + (1 * 2) = $15,001
     * If I purchase 100,001 bicycles, what would the total price be?
     *
     * @param int $qty
     * @param array $tiers
     * @return float
     */
    public static function getTotalPriceTierAtQty(int $qty, array $tiers): float
    {
        if ($qty == 0) {
            return 0.0;
        } elseif ($qty <= 10000) {
            // making use of price tiers to calculate total price
            return $qty * $tiers[0];
        } elseif ($qty <= 100000) {
            // total cost for first tier
            $firstTier = 10000 * $tiers[0];
            // total cost for second tier
            $secondTier = ($qty - 10000) * $tiers[10001];
            return $firstTier + $secondTier;
        } else {
            // total cost for first tier
            $firstTier = 10000 * $tiers[0];
            // total cost for second tier
            $secondTier = 90000 * $tiers[10001];
            // total cost for third tier
            $thirdTier = ($qty - 100000) * $tiers[100001];
            return $firstTier + $secondTier + $thirdTier;
        }
    }

    /**
     * Task: Write a function to return an array of prices at each quantity
     *
     * Question A:
     * A user purchased 933, 22012, 24791 and 15553 bicycles respectively in Jan, Feb, Mar, April
     * The management would like to know how much to bill this user for each of those month.
     * This user is on a special pricing tier where the quantity does not reset each month and is thus CUMULATIVE.
     *
     * Question B:
     * A user purchased 933, 22012, 24791 and 15553 bicycles respectively in Jan, Feb, Mar, April
     * The management would like to know how much to bill this user for each of those month.
     * This user is on the typical pricing tier where the quantity RESETS each month and is thus NOT CUMULATIVE.
     *
     */
    public static function getPriceAtEachQty(array $qtyArr, array $tiers, bool $cumulative = false): array
    {
        $priceArray = [];
        if ($cumulative) { // the case where user is on the SPECIAL pricing tier, CUMULATIVE
            $totalQty = 0;
            for ($i = 0; $i < count($qtyArr); $i++) {
                // adding the qty of each month to the existing total quantity
                $totalQty += $qtyArr[$i];
                // checking the cumulative qty thus far.
                if ($totalQty <= 10000) { // if the total is within first tier, calculation is straightforward.
                    $cost = $qtyArr[$i] * $tiers[0];
                    array_push($priceArray, floatval($cost));
                } elseif ($totalQty <= 100000) { // if total has crossed into second tier, we need to check for TWO conditions.
                    if ($qtyArr[$i - 1] < 10000) { // CONDITION 1: if total has only crossed into second tier in current month, there is a difference that still belongs in the first tier.
                        $difference = (10000 - $qtyArr[$i - 1]);
                        $firstTier = $difference * $tiers[0];
                        $secondTier = ($qtyArr[$i] - $difference) * $tiers[10001];
                        $cost = $firstTier + $secondTier;
                        array_push($priceArray, floatval($cost)); // convert to float to pass tests
                    } else { // CONDITION 2: the total is already in second tier, and unit cost is simply 1.
                        $cost = $qtyArr[$i] * $tiers[10001];
                        array_push($priceArray, floatval($cost)); // convert to float to pass tests
                    }
                }
            }  
        } else { // the case where user is NOT on special pricing tier
            for ($i = 0; $i < count($qtyArr); $i++) {
                // tried using $this->getTotalPriceTierAtQty() but error was thrown as $this cannot be used in a static method.
                // reusing the getTotalPriceTierAtQty method defined above so code is not repeated.
                $cost = self::getTotalPriceTierAtQty($qtyArr[$i], $tiers);
                array_push($priceArray, floatval($cost));
            }
        }
       return $priceArray;
    }
}

$priceHelper = new PriceHelper;
$priceTier = [
    0 => 1.5, // 0 - 10,000 qty => $1.5
    10001 => 1, // 10,000 - 100,000 qty => $1
    100001 => 0.5, // 100,001 & more => $0.5
];
// echo $priceHelper->getUnitPriceTierAtQty(10000, $priceTier);

// echo $priceHelper->getTotalPriceTierAtQty(100001, $priceTier);
print_r($priceHelper-> getPriceAtEachQty([933, 22012, 24791, 15553], $priceTier, false));
