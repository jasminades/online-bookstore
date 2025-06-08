<?php

require_once './rest/dao/ReviewsDAO.php';
require_once './rest/dao/BooksDAO.php';
require_once './rest/dao/UsersDAO.php';

class ReviewsService {
    private $reviewsDAO;
    private $booksDAO;
    private $usersDAO;

    public function __construct() {
        $this->reviewsDAO = new ReviewsDAO();
        $this->booksDAO = new BooksDAO(); 
        $this->usersDAO = new UsersDAO(); 
    }

    public function getAll() {
        return $this->reviewsDAO->getAll(); 
    }


    public function getAllByBook($book_id) {
        $reviews = $this->reviewsDAO->getAllByBook($book_id);
        if (!$reviews) {
            throw new Exception("No reviews found for this book.");
        }
        return $reviews;
    }

    public function get_by_id($id) {
        $review = $this->reviewsDAO->get_by_id($id);  
        if (!$review) {
            throw new Exception("Review not found.");
        }
        return $review;
    }

    private function validateReviewData($data, $isUpdate = false) {
        $errors = [];

        if (!isset($data['book_id']) || !$this->booksDAO->getById($data['book_id'])) { 
            $errors[] = "Invalid or non-existent book.";
        }

        if (!isset($data['user_id']) || !is_array($this->usersDAO->get_by_id($data['user_id']))) {
            $errors[] = "Invalid or non-existent user.";
        }


        if (!isset($data['rating']) || !is_numeric($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
            $errors[] = "Rating must be an integer between 1 and 5.";
        }

        if (isset($data['comment']) && (strlen($data['comment']) < 5 || strlen($data['comment']) > 500)) {
            $errors[] = "Comment must be between 5 and 500 characters.";
        }

        if (!empty($errors)) {
            throw new Exception(implode(" ", $errors));
        }
    }

    public function createReview($data) {
        $this->validateReviewData($data);
        $review = $this->reviewsDAO->create($data['book_id'], $data['user_id'], $data['rating'], $data['comment']);
        return $review;
    }

    public function updateReview($id, $data) {
        if (!$this->reviewsDAO->get_by_id($id)) { 
            throw new Exception("Review not found.");
        }

        $this->validateReviewData($data, true);
        $updatedReview = $this->reviewsDAO->update($id, $data['rating'], $data['comment']);
        return $updatedReview;
    }

    public function deleteReview($id) {
        if (!$this->reviewsDAO->get_by_id($id)) {  
            throw new Exception("Review not found.");
        }

        $this->reviewsDAO->delete($id);
        return ["message" => "Review deleted successfully"];
    }

   public function getAllByUser($user_id) {
        $reviews = $this->reviewsDAO->getAllByUser($user_id);
        if (!$reviews) {
            throw new Exception("No reviews found for this user.");
        }
        return $reviews;
    }

}
