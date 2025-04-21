<?php

require_once './dao/BooksDAO.php';

class BooksService{
    private $booksDAO;

    public function __construct(){
        $this->booksDAO = new BooksDAO();
    }

    public function getAllBooks(){
        return $this->booksDAO->getAll();
    }

    public function getBookById($id){
        $book = $this->booksDAO->getById($id);

        if(!$book){
            throw new Exception("Book not found");
        }
        return $book;
    }


    // validation
    private function validateBookData($data, $isUpdate = false) {
        $errors = [];

        if (empty($data['title'])) {
            $errors[] = "Title is required";
        }

        if (empty($data['author'])) {
            $errors[] = "Author is required";
        }

        if (empty($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
            $errors[] = "Price is required and must be a positive number";
        }

        if (empty($data['category_id'])) {
            $errors[] = "Category ID is required";
        }

        if ($isUpdate || isset($data['id'])) {
            $book = $this->booksDAO->getById($data['id']);
            if (!$book) {
                $errors[] = "Book not found for update or delete";
            }
        }

        if (!empty($errors)) {
            throw new Exception(implode(" ", $errors));
        }
    }


    public function createBook($data){  
        $this->validateBookData($data);

        return $this->booksDAO->create($data['title'], $data['author'], $data['price'], $data['category_id']);
    }

    public function updateBook($id, $data) {
        $this->validateBookData($data, true);
        return $this->booksDAO->update($id, $data['title'], $data['author'], $data['price'], $data['category_id']);
    }

    public function deleteBook($id) {
        $this->validateBookData(['id' => $id], true);
        return $this->booksDAO->delete($id);
    }

}