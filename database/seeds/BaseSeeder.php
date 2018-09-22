<?php

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Product;

class BaseSeeder extends Seeder {

    protected $supplierMap = [];

    protected $categoryMap = [];

    protected $productMap = [];

    protected function getSupplierId($key)
    {
        if (array_key_exists($key, $this->supplierMap)) {
            return $this->supplierMap[$key];
        }

        $query = Supplier::query()
            ->where('name', $key)
            ->orWhere('code', $key)
            ->first();

        if ($query) {
            return $this->supplierMap[$key] = $query->id;
        }

        return null;
    }

    protected function getCategoryId($name)
    {
        if (array_key_exists($name, $this->categoryMap)) {
            return $this->categoryMap[$name];
        }

        $query = Category::where('name', $name)->first();

        if ($query) {
            return $this->categoryMap[$name] = $query->id;
        }

        return null;
    }

    protected function getProductId($upc)
    {
        if (array_key_exists($upc, $this->productMap)) {
            return $this->productMap[$upc];
        }

        $query = Product::where('upc', $upc)->first();

        if ($query) {
            return $this->productMap[$upc] = $query->id;
        }

        return null;
    }
}
