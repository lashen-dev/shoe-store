<?php
require_once 'app/models/Shoe.php';

class HomeController {
    public function index() {
        global $pdo;
        $shoeModel = new Shoe($pdo);
        $shoes = $shoeModel->getAllShoes();
        require 'app/views/home/index.php';
    }
}
?>