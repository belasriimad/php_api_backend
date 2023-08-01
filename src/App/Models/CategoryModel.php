<?php

namespace App\Models;
use App\Database\Database as DB;
use PDO;


class CategoryModel
{
    private $conn;

    public function __construct()
    {
        $database = new DB;
        $this->conn = $database->connect();
    }

    public function fetchAllCategories()
    {
        $stmt = $this->conn->prepare("SELECT * FROM categories");
        $stmt->execute();

        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $categories;
    }
}