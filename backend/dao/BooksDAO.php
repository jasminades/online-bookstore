<?php

require_once __DIR__ . "/BaseDao.php";

class BooksDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("books");
    }

    public function getById($id)
    {
        return $this->query_unique("SELECT * FROM books WHERE id = :id", ['id' => $id]);
    }

    public function update($id, $title, $author, $price, $category_id)
    {
        $entity = [
            'title' => $title,
            'author' => $author,
            'price' => $price,
            'category_id' => $category_id
        ];
        return $this->execute(
            "UPDATE books SET title = :title, author = :author, price = :price, category_id = :category_id WHERE id = :id",
            array_merge($entity, ['id' => $id])
        );
    }

    public function delete($id)
    {
        return $this->execute("DELETE FROM books WHERE id = :id", ['id' => $id]);
    }

    public function create($title, $author, $price, $category_id)
    {
        $entity = [
            'title' => $title,
            'author' => $author,
            'price' => $price,
            'category_id' => $category_id,
            'created_at' => date('Y-m-d H:i:s')
        ];
        return $this->insert("books", $entity);
    }

    public function get_all()
    {
        return $this->query("SELECT * FROM books", []);
    }
}
