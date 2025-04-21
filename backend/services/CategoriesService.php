<?php

require_once './dao/CategoriesDAO.php';

class CategoriesService{
    private $categoriesDAO;

    public function __construct(){
        $this->categoriesDAO = new CategoriesDAO();
    }


    public function getAllCategories(){
        return $this->categoriesDAO->getAll();
    }

    public function getCategoryById($id){
        $category = $this->categoriesDAO->getById($id);

        if(!$category){
            throw new Exception("Category not found");
        }
        return $category;
    }


    // validation
    private function validateCategoryData($data, $isUpdate = false) {
        $errors = [];

        if (empty($data['name'])) {
            $errors[] = "Category name is required";
        }

        if ($isUpdate || isset($data['id'])) {
            $category = $this->categoriesDAO->getById($data['id']);
            if (!$category) {
                $errors[] = "Category not found for update or delete";
            }
        }

        if (!empty($errors)) {
            throw new Exception(implode(" ", $errors));
        }
    }
    

    public function createCategory($data){
        $this->validateCategoryData($data);

        return $this->categoriesDAO->create($data['name']);
    }


    public function updateCategory($id, $data){
        $this->validateCategoryData($data, true);

        return $this->categoriesDAO->update($id, $data['name']);
    }
    
    public function deleteCategory($id){
        $this->validateCategoryData(['id' => $id], true);
        return $this->categoriesDAO->delete($id);
    }
}