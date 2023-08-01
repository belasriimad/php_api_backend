<?php

namespace App\Controllers;
use App\Models\CategoryModel as Category;

class CategoryController
{
    private $model;

    public function __construct()
    {
        $this->model = new Category;
    }

    public function index()
    {
        $categories = $this->model->fetchAllCategories();
        echo json_encode([
            'categories' => $categories
        ]);
    }

}