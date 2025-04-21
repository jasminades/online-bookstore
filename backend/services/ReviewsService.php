<?php

require_once './dao/ReviewsDAO.php';
require_once './dao/BooksDAO.php';
require_once './dao/UsersDAO.php';

class ReviewsService {
    private $reviewsDAO;

    public function __construct() {
        $this->reviewsDAO = new ReviewsDAO();
    }

    
    public function getAllByBook($book_id) {
        $reviews = $this->reviewsDAO->getAllByBook($book_id);
        if (!$reviews) {
            throw new Exception("No reviews found for this book.");
        }
        return $reviews;
    }

    
    public function getById($id) {
        $review = $this->reviewsDAO->getById($id);
        if (!$review) {
            throw new Exception("Review not found.");
        }
        return $review;
    }

    private function validateReviewData($data, $isUpdate = false) {
        $errors = [];

        if (!isset($data['book_id']) || !BooksDAO::getById($data['book_id'])) {
            $errors[] = "Invalid or non-existent book.";
        }

        if (!isset($data['user_id']) || !UsersDAO::getById($data['user_id'])) {
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
        if (!$this->reviewsDAO->getById($id)) {
            throw new Exception("Review not found.");
        }

        $this->validateReviewData($data, true);
        $updatedReview = $this->reviewsDAO->update($id, $data['rating'], $data['comment']);
        return $updatedReview;
    }

    
    public function deleteReview($id) {
        if (!$this->reviewsDAO->getById($id)) {
            throw new Exception("Review not found.");
        }

        $this->reviewsDAO->delete($id);
        return ["message" => "Review deleted successfully"];
    }
}
