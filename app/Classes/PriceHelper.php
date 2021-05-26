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

        // keep track of the previous tier
        $storePrevTier = 0;
        foreach($tiers as $tierStart => $unitCost) { // loop through all the tiers
            if ($qty == 0) {
                return 0.0;
            } elseif ($qty <= $tierStart - 1) {  // takes care of all the cases where qty is less than the final tier
                return $tiers[$storePrevTier];
            }
            $storePrevTier = $tierStart; 
        }
        return $tiers[$storePrevTier]; // the final tier will exit the loop
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
        // keep track of the previous tier and the cumulative sum of previous tiers
        $storePrevTier = 0; 
        $tierSum = 0;
        foreach($tiers as $tierStart => $unitCost) { 
            if ($qty == 0) {
                return 0.0;
            } elseif ($qty <= $tierStart - 1) { // takes care of all the cases where qty is less than the final tier
                if ($storePrevTier == 0) { // the first special case where the tier is 0.
                    $sum = ( $qty - $storePrevTier) * $tiers[$storePrevTier];    
                } else { // the rest of the cases where 1 has to be taken off the tier to find the excess over the tier.
                    $excess =  ( $qty - ($storePrevTier - 1));
                    $sum = ($excess) * $tiers[$storePrevTier];
                }
                return $sum + $tierSum; // return the excess sum over the current tier plus accumulated amount.
            }
            if ($storePrevTier == 0 && $tierStart) { // the first special case where the previous tier is 0 and the current tier is ****1. extra 1 must be taken off.
                $tierSum += ($tierStart - $storePrevTier - 1) * $tiers[$storePrevTier]; 
            } else { // the rest of the cases where (****1 - ****1) will deal with the 1 automatically.
                $tierSum += ($tierStart - $storePrevTier) * $tiers[$storePrevTier]; 
            }
            $storePrevTier = $tierStart; 
        }
        // final tier exits the loop
        $excess =  ( $qty - ($storePrevTier - 1));
        $sum = $sum = ($excess) * $tiers[$storePrevTier];
        return $sum + $tierSum;
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
            // keep track of the previous tier and the cumulative sum of previous tiers
            $storePrevTier = 0; 
            $storeCrossTier = 0; // seperately keep track of previous tier that only changes for the event when a tier happens to be crossed.
            // $tierSum = 0;
            for ($i = 0; $i < count($qtyArr); $i++) {
                
                $totalQty += $qtyArr[$i];
                // checking the cumulative qty thus far.
                foreach($tiers as $tierStart => $unitCost)  { 
                    if ($totalQty <= $tierStart - 1) { // the check is now done against the total cumulative quantity. check if it is within the current tier
                        if (array_key_exists($i - 1, $qtyArr) && $qtyArr[$i - 1]) { // check to determine if first month. error handle array key of -1 during first month
                            echo "CURRENT TIER: $tierStart ";
                            echo "PREV TIER: $storePrevTier ";
                            if ($totalQty - $qtyArr[$i] <= $storePrevTier) { // check if the current tier was just crossed into or already crossed.    
                                $difference = (($storePrevTier - 1) - ($totalQty - $qtyArr[$i]));
                                echo "DIFFERENCE: $difference ";
                                $firstTier = $difference * $tiers[$storeCrossTier];
                                echo "FIRST TIER: $firstTier ";
                                $secondTier = ($qtyArr[$i] - $difference) * $tiers[$storePrevTier];
                                $cost = $firstTier + $secondTier;
                                array_push($priceArray, floatval($cost));
                                $storeCrossTier = $storePrevTier;
                                break;
                            } else {
                                $cost = $qtyArr[$i] * $tiers[$storePrevTier];
                                array_push($priceArray, floatval($cost));
                                break;  
                            }
                        } else { // only runs for the first month as $qtyArr[$i - 1] does not exist
                            if (array_search($qtyArr[$i], $qtyArr) == 0) {
                                $cost = $qtyArr[$i] * $tiers[$storePrevTier];
                                array_push($priceArray, floatval($cost));
                                break;
                            }
                            // echo "ran ELSE";
                            // echo "$tierStart";
                        }
                    }
                    $storePrevTier = $tierStart; 
                    echo "BOTTOM: $storePrevTier ";
                }
                // final tier exits the loop
                // $excess =  ( $totalQty - ($storePrevTier - 1));
                // $sum = $sum = ($excess) * $tiers[$storePrevTier];
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

// echo $priceHelper->getTotalPriceTierAtQty(70001, $priceTier2);
print_r($priceHelper-> getPriceAtEachQty([933, 22012, 24791, 15553], $priceTier1, false));
print_r($priceHelper-> getPriceAtEachQty([933, 22012, 24791, 15553, 36711, 1], $priceTier1, true));
