<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductChildSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $res = DB::table('products')->get();

        if($res->count())
        {
            foreach($res as $product)
            {
                //$items = DB::connection('old_finance')->table('product_child')->where('parent_id',$product->id)->get(); // inconsistencies with databases
                $items = DB::connection('old_finance')
                    ->table('product_child')
                    ->select('product_child.*')
                    ->join('product','product.item_id', '=', 'product_child.parent_id')
                    ->where('product.short_description',$product->title)->get();

                if( $items && $items->count() ) {

                    foreach($items as $item)
                    {

                        $name = null;
                        $get_name =  DB::connection('old_finance')->table('product')->where('item_id',$item->child_id)->first();

                        if( $get_name ) {
                            $name = $get_name->short_description;
                        }

                        // get the sub product from new database
                        $prod = DB::table('products')->where('title',$name)->first();

                        DB::table('product_children')->insert([
                            'parent_id'             => $product->id,
                            'product_id'            => $prod->id,
                            'product_title'         => $name,
                            'qty'                   => $item->quantity,
                            'weight'                => $item->weight,
                            'unit_of_measure_id'    => $item->uom ?: null,
                            'weight_kg'             => $item->kg_weight,
                            'created_at'            => date('Y-m-d H:i:s'),
                            'updated_at'            => date('Y-m-d H:i:s')
                        ]);
                    }
                }
            }
        }
    }
}
