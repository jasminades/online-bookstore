<?php

require_once './dao/BooksDAO.php';
require_once './services/BaseService.php';

class BooksService extends BaseService {
    public function __construct() {
        parent::__construct(new BooksDAO()); 
    }

    public function getBookById($id) {
        $book = $this->dao->getById($id);
        if (!$book) {
            throw new Exception("Book not found");
        }
        return $book;
    }

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
            $book = $this->dao->getById($data['id']);
            if (!$book) {
                $errors[] = "Book not found for update or delete";
            }
        }

        if (!empty($errors)) {
            throw new Exception(implode(" ", $errors));
        }
    }

    public function createBook($data) {
        $this->validateBookData($data);
        return $this->dao->create($data['title'], $data['author'], $data['price'], $data['category_id']);
    }

    public function updateBook($id, $data) {
        $this->validateBookData(array_merge($data, ['id' => $id]), true);
        return $this->dao->update($id, $data['title'], $data['author'], $data['price'], $data['category_id']);
    }

    public function deleteBook($id) {
        $book = $this->dao->getById($id);
        if (!$book) {
            throw new Exception("Book not found");
        }

        return $this->dao->delete($id);
        }

    public function featuredBooks() {
        return $this->dao->get_all_featured_books();
    }

}
