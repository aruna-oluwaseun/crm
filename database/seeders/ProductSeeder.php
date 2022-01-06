<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Run from old_finance
        $res = DB::connection('old_finance')->table('product')->get();

        if($res->count())
        {
            foreach($res as $item)
            {
                $uom = 1;
                switch ($item->unit)
                {
                    case 'g' :
                        break;
                    case 'Kg' :
                        $uom = 2;
                        break;
                    case 'ml':
                        $uom = 3;
                        break;
                    case 'L' :
                        $uom = 4;
                        break;
                    case 'm2' :
                        $uom = 5;
                        break;
                    default: $uom = 1;
                }

                $commodity_code = null;
                if($item->type) {
                    $commodity_code = $item->type == 1 ? '2525.20.00.00' : null;
                }

                $product_id = DB::table('products')->insertGetId([
                    'title'                 => $item->short_description,
                    'code'                  => $item->item_ref,
                    'slug'                  => Str::slug(ucwords($item->short_description),'-'),
                    'description'           => $item->description,
                    'short_description'     => $item->short_description,
                    'weight'                => $item->Weight,
                    'unit_of_measure_id'    => $uom,
                    'weight_kg'             => null,
                    'commodity_code'        => $commodity_code,
                    'is_available_online'   => $item->is_manufactured ? 1 : 0,
                    'is_manufactured'       => $item->is_manufactured ? 1 : 0,
                    'is_discountable'       => $item->discountable ? 1 : 0,
                    'is_training'           => 0,
                    'is_assessment'         => 0,
                    'is_free_shipping'      => 0,
                    'is_packaged'           => $item->is_packaged ? 1 : 0,
                    'assembly_minutes'      => $item->Assembly_time ?: null,
                    'product_type_id'       => $item->type ?: null,
                    'created_by'            => 2, // Mike
                    'created_at'            => date('Y-m-d H:i:s'),
                    'updated_at'            => date('Y-m-d H:i:s'),
                    'franchise_id'          => 1
                ]);

                // Link suppliers
                if( $item->supplier_id )
                {
                    DB::table('product_supplier')->insert([
                        'product_id'            => $product_id,
                        'supplier_id'           => $item->supplier_id,
                        'default_supplier'      => true,
                        'code'                  => $item->supplier_ref,
                        'cost_to_us'            => $item->supplier_cost,
                        'vat_type_id'           => null,
                        'created_at'            => date('Y-m-d H:i:s'),
                        'updated_at'            => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }
    }
}
