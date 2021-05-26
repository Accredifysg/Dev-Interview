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

        // $currentTier = $tiers[0];
        // foreach($tiers as $tierStart => $unitCost) {
        //     if ($qty == 0) { // taking care of the first case where $qty is 0 
        //         return 0.0;
        //     } elseif ($qty < $tierStart) { // takes care of the tiers inbetween
        //         return $currentTier;
        //     } elseif ($qty >= array_key_last($tiers)) { // the case where $qty is more than the final tier
        //         return end($tiers);
        //     }
        //     $currentTier = $unitCost;
        // }

        $storePrevTier = 0;
        foreach($tiers as $tierStart => $unitCost) { 
            if ($qty == 0) {
                return 0.0;
            } elseif ($qty <= $tierStart - 1) {
                return $tiers[$storePrevTier];
            }
            $storePrevTier = $tierStart;
        }
        return $tiers[$storePrevTier];
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
        $storePrevTier = 0;
        $tierSum = 0;
        foreach($tiers as $tierStart => $unitCost) { 
            if ($qty == 0) {
                return 0.0;
            } elseif ($qty <= $tierStart - 1) {
                if ($storePrevTier == 0) {
                    $sum = ( $qty - $storePrevTier) * $tiers[$storePrevTier];    
                } else {
                    $excess =  ( $qty - ($storePrevTier - 1));
                    echo "excess: ($excess) ";
                    $sum = ($excess) * $tiers[$storePrevTier];
                }
                return $sum + $tierSum;
            }
            // echo "storePrvtier: ($storePrevTier) ";
            // echo "tierstart: ($tierStart) ";
            // echo "unitcost: ($tiers[$tierStart]) ";
            // echo "tiersum(before): ($tierSum) " ;
            if ($storePrevTier == 0 && $tierStart) {
                $tierSum += ($tierStart - $storePrevTier - 1) * $tiers[$storePrevTier]; 
            } else {
                $tierSum += ($tierStart - $storePrevTier) * $tiers[$storePrevTier]; 
            }
            $storePrevTier = $tierStart;
            echo "tierSum(Loop): ($tierSum) ///";
        }
        echo "storePrvtier: ($storePrevTier) ";
        echo "tierstart: ($tierStart) ";
        echo "unitcost: ($tiers[$storePrevTier]) ";
        echo "tierSum: ($tierSum) ";
        $excess =  ( $qty - ($storePrevTier - 1));
        $sum = $sum = ($excess) * $tiers[$storePrevTier];
        return $sum + $tierSum;


                // // store the previous tier, unit cost, and total tier sum
                // $prevUnit = $tiers[0]; 
                // $prevTier = 0;
                // $tierSum = 0;
        
                // foreach($tiers as $tierStart => $unitCost) {
                //     if ($qty == 0) { // taking care of the first case where $qty is 0 
                //         return 0.0;
                //     } elseif ($qty <= $tierStart - 1) { // takes care of the tiers inbetween the first and final
                //         $sum = ($qty - $prevTier) * $prevUnit; // sum the excess qty over the previous tier with the previous unit cost
                //         return $sum + $tierSum; // return the total
                //     } else {
                //         if($tierStart == 0) { // if first tier is 0, should not take -1 off.
                //             $tierSum += $tierStart * $prevUnit;
                //             $prevTier = $tierStart;
                //         } else { // the rest of the tiers. -1 as the sum for the previous tier should not take into account the additional unit.
                //             $tierSum += ($tierStart - 1) * $prevUnit;
                //             $prevTier = ($tierStart - 1);
                //         }
                //         $prevUnit = $unitCost;
                //     }
                // }
                // $sum = ($qty - $prevTier) * $prevUnit;
                // return $sum + $tierSum;
        
                // Issue: Stuck on the final tier which is not giving the right output.
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
$priceTier1 = [
    0 => 1.5, // 0 - 10,000 qty => $1.5
    10001 => 1, // 10,000 - 100,000 qty => $1
    100001 => 0.5, // 100,001 & more => $0.5
];
$priceTier2 = [
    0 => 1.5,
    5001 => 1,
    60001 => 0.5,
    70001 => 0.4
];
// echo $priceHelper->getUnitPriceTierAtQty(5, $priceTier1);

echo $priceHelper->getTotalPriceTierAtQty(70001, $priceTier2);
// print_r($priceHelper-> getPriceAtEachQty([933, 22012, 24791, 15553], $priceTier, false));
