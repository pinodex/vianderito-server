<?php

use Illuminate\Database\Seeder;

class BaseSeeder extends Seeder {

    protected $manufacturerMap = [];

    protected $categoryMap = [];

    protected $productMap = [];

    protected function getManufacturerId($key)
    {
        if (array_key_exists($key, $this->manufacturerMap)) {
            return $this->manufacturerMap[$key];
        }

        $query = DB::table('manufacturers')
            ->where('name', $key)
            ->orWhere('code', $key)
            ->first();

        if ($query) {
            return $this->manufacturerMap[$key] = $query->id;
        }

        return null;
    }

    protected function getCategoryId($name)
    {
        if (array_key_exists($name, $this->categoryMap)) {
            return $this->categoryMap[$name];
        }

        $query = DB::table('categories')->where('name', $name)->first();

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

        $query = DB::table('products')->where('upc', $upc)->first();

        if ($query) {
            return $this->productMap[$upc] = $query->id;
        }

        return null;
    }
}
