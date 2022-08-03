<?php

namespace App\Services;


class Helper
{

    public static function validateCPF($value): bool
    {
        $cpf = preg_replace('/[^0-9]/', "", $value);
        if (strlen($cpf) != 11 || preg_match('/([0-9])\1{10}/', $cpf)) {
            return false;
        }
        $number_quantity_to_loop = [9, 10];
        foreach ($number_quantity_to_loop as $item) {
            $sum = 0;
            $number_to_multiplicate = $item + 1;
            for ($index = 0; $index < $item; $index++) {
                $sum += $cpf[$index] * ($number_to_multiplicate--);
            }
            $result = (($sum * 10) % 11);
            if ($cpf[$item] != $result) {
                return false;
            }
        }
        return true;
    }

    public static function brDate( $date )
    {
        if( gettype( $date ) === "string" ) {
            $date = strtotime($date);
        } else {
            $date = $date->timestamp;
        }
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        return strftime('%B de %Y', $date);
    }

    public static function getClosest($arr, $target)
    {
        if( in_array($target, $arr)) {
            return $target;
        }
        $closest = null;
        foreach ($arr as $item) {
            if ($closest === null || abs($target - $closest) > abs($item - $target)) {
                $closest = $item;
            }
        }
        return $closest;
    }
}
