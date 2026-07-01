<?php

require_once "../core/Model.php";

class Product extends Model
{
    private static $products = [
        ['id' => 1, 'name' => 'Laptop', 'price' => 1500],
        ['id' => 2, 'name' => 'Mouse', 'price' => 25],
        ['id' => 3, 'name' => 'Keyboard', 'price' => 45],
    ];

    public function all()
    {
        return self::$products;
    }

    public function create($name, $price)
    {
        self::$products[] = [
            'id' => count(self::$products) + 1,
            'name' => $name,
            'price' => $price
        ];
    }
}