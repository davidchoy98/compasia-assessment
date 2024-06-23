<?php

namespace App\Imports;

use App\Models\Brands;
use App\Models\Capacities;
use App\Models\ProductModels;
use App\Models\Products;
use App\Models\ProductTypes;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class ProductsImport implements ToModel, WithChunkReading, WithHeadingRow, WithUpserts, ShouldQueue
{
    use Importable;

    private const STATUS_SOLD = 'sold';

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $type_id = ProductTypes::firstOrCreate(['name' => $row['types']])->id;
        $brand_id = Brands::firstOrCreate(['name' => $row['brand']])->id;
        $capacity_id = Capacities::firstOrCreate(['name' => $row['capacity']])->id;
        $model_id = ProductModels::firstOrCreate([
            'capacity_id'=> $capacity_id,
            'name' => $row['model']
        ])->id;



        $product = Products::firstOrCreate([
            'type_id' => $type_id,
            'brand_id' => $brand_id,
            'model_id' => $model_id,
            'product_id' => $row['product_id']
        ]);

        $product->quantity = $row['status'] === self::STATUS_SOLD ? --$product->quantity : ++$product->quantity;
        $product->save();

        return $product;
    }

    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return ['brand_id', 'model_id'];
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
