<?php

namespace App\Models;

class Helper
{
   public static function randomToken() {
        $alphabet = "0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        $array_lengths = array(2,4,4,3);
        foreach($array_lengths as $v){
            for ($i = 0; $i < $v; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            $pass[] = '-';
        }
        return rtrim(implode($pass),'-');
    }

}
