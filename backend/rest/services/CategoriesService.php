<?php

require_once './rest/dao/CategoriesDAO.php';

class CategoriesService{
    private $categoriesDAO;

    public function __construct(){
        $this->categoriesDAO = new CategoriesDAO();
    }


    public function getAllCategories(){
        return $this->categoriesDAO->get_all();
    }

    public function getCategoryById($id){
        $category = $this->categoriesDAO->get_by_id($id);
        if(!$category){
            throw new Exception("Category not found");
        }
        return $category;
    }

    private function validateCategoryData($data, $isUpdate = false, $isDelete = false) {
        $errors = [];

        if (!$isDelete && empty($data['name'])) {
            $errors[] = "Category name is required";
        }

        if ($isUpdate || $isDelete || isset($data['id'])) {
            $category = $this->categoriesDAO->get_by_id($data['id']);
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
        $this->validateCategoryData(['id' => $id], false, true); 
        return $this->categoriesDAO->deleteCategory($id);
    }
}