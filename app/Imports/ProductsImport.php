<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Product([
            'name'           => $row['name'],
            'category'       => $row['category'],
            'unit'           => $row['unit'],
            'stock_quantity' => $row['stock_quantity'],
            'cost_price'     => $row['cost_price'],
            'selling_price'  => $row['selling_price'],
        ]);
    }

    public function rules(): array
    {
        return [
            'name'           => 'required|string',
            'category'       => 'required|string',
            'unit'           => 'required|string',
            'stock_quantity' => 'required|integer|min:0',
            'cost_price'     => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
        ];
    }
}
