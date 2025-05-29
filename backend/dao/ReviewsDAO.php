<?php

require_once __DIR__ . "/BaseDao.php";

class ReviewsDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("reviews");
    }

    
    public function getAll() {
        return $this->query("SELECT * FROM reviews", []);
    }


    public function create($book_id, $user_id, $rating, $comment)
    {
        $review = [
            'book_id' => $book_id,
            'user_id' => $user_id,
            'rating' => $rating,
            'comment' => $comment,
            'created_at' => date('Y-m-d H:i:s')
        ];
        return $this->insert("reviews", $review);
    }

    public function getAllByBook($book_id)
    {
        return $this->query("SELECT * FROM reviews WHERE book_id = :book_id", ['book_id' => $book_id]);
    }

    public function get_by_id($id)
    {
        return $this->query_unique("SELECT * FROM reviews WHERE id = :id", ['id' => $id]);
    }

    public function update($id, $rating, $comment)
    {
        return $this->execute(
            "UPDATE reviews SET rating = :rating, comment = :comment WHERE id = :id",
            ['rating' => $rating, 'comment' => $comment, 'id' => $id]
        );
    }

    public function delete($id)
    {
        return $this->delete($id);
    }
}
