<?php

require_once "../core/Controller.php";
require_once "../app/models/Product.php";

class ProductController extends Controller
{
    public function index()
    {
        $productModel = new Product();
        $products = $productModel->all();

        $this->view("products.index", ['products' => $products]);
    }

    public function create()
    {
        $this->view("products.create");
    }

    public function store()
    {
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? 0;

        $productModel = new Product();
        $productModel->create($name, $price);

        echo "Tạo sản phẩm thành công! <br>";
        echo "<a href='/products'>Quay lại danh sách</a>";
    }
}