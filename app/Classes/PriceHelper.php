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
                        if (array_key_exists($i - 1, $qtyArr) && $qtyArr[$i - 1]) { // check to determine if first month. error handle array key of -1 for first month
                            if ($totalQty - $qtyArr[$i] <= $storePrevTier) { // check if the current tier was just crossed into or already crossed.    
                                $difference = (($storePrevTier - 1) - ($totalQty - $qtyArr[$i])); // the difference between the tier and the previous total;
                                $firstTier = $difference * $tiers[$storeCrossTier]; // amount for the tier before it was crossed.
                                $secondTier = ($qtyArr[$i] - $difference) * $tiers[$storePrevTier]; // amount for the tier after it was crossed.
                                $cost = $firstTier + $secondTier;
                                array_push($priceArray, floatval($cost));
                                $storeCrossTier = $storePrevTier; // update the crosstier. 
                                break;
                            } else { // the case where the tier was already crossed. simple calculation.
                                $cost = $qtyArr[$i] * $tiers[$storePrevTier];
                                array_push($priceArray, floatval($cost));
                                break; 
                            }
                        } else { // only runs for the first month as $qtyArr[$i - 1] does not exist
                            if (array_search($qtyArr[$i], $qtyArr) == 0) { // check to ensure only run for first element
                                $cost = $qtyArr[$i] * $tiers[$storePrevTier];
                                array_push($priceArray, floatval($cost));
                                break;
                            }
                        }       
                    }
                    $storePrevTier = $tierStart; // update the prevTier store.
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
