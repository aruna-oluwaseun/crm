<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitOfMeasure extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**/
    public static function convert($unit_from = 'g', $unit_to = 'Kg', $value = 0 ): object
    {
        // convert ids to unit names
        if(is_int($unit_from)) {
            switch ($unit_from)
            {
                case 1 :
                    $unit_from = 'g';
                    break;
                case 2 :
                    $unit_from = 'Kg';
                    break;
                case 3 :
                    $unit_from = 'ml';
                    break;
                case 4 :
                    $unit_from = 'L';
                    break;
                case 5 :
                    $unit_from = 'm2';
                    break;
            }
        }

        if(is_int($unit_to)) {
            switch ($unit_to)
            {
                case 1 :
                    $unit_to = 'g';
                    break;
                case 2 :
                    $unit_to = 'Kg';
                    break;
                case 3 :
                    $unit_to = 'ml';
                    break;
                case 4 :
                    $unit_to = 'L';
                    break;
                case 5 :
                    $unit_to = 'm2';
                    break;
            }
        }

        $allowed_units = ['g','Kg','ml','L','m2'];

        if(!in_array($unit_from, $allowed_units) || !in_array($unit_to, $allowed_units))
        {
            return (object) [
                'success' => false,
                'message' => 'You passed in-correct value the allowed values are '.print_r($allowed_units, true)
            ];
        }

        if(!$value) {
            return (object) [
                'success' => false,
                'message' => 'The value to convert is required'
            ];
        }

        $multiply = 0;

        // Convert g
        if($unit_from == 'g') {
            if($unit_to == 'Kg' || $unit_to == 'g') {
                $multiply = 0.001;
            }
        }

        // Convert Kg
        if($unit_from == 'Kg') {
            if($unit_to == 'Kg') {
                $multiply = 1;
            }
            if($unit_to == 'g') {
                $multiply = 1000;
            }
        }

        // Convert ml
        if($unit_from == 'ml') {
            if($unit_to == 'Kg' || $unit_to == 'ml' || $unit_to == 'L') {
                $multiply = 0.001;
            }
        }

        // Convert L
        if($unit_from == 'L') {
            if($unit_to == 'L') {
                $multiply = 1;
            }
            if($unit_to == 'ml') {
                $multiply = 1000;
            }
            if($unit_to == 'm2') {
                $multiply = 0.001;
            }
        }

        // Convert m2
        if($unit_from == 'm2') {
            if($unit_to == 'L') {
                $multiply = 1;
            }
            if($unit_to == 'Kg') {
                $multiply = 10;
            }
            if($unit_to == 'm2') {
                $multiply = 10;
            }
        }

        return (object) [
            'success' => true,
            'value' => $value*$multiply
        ];
    }

}
