<?php

require_once __DIR__ . "/BaseDao.php";

class CategoriesDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("categories");
    }

    public function create($name)
    {
        $entity = ['name' => $name];
        return $this->insert("categories", $entity);
    }

    public function getAll()
    {
        return $this->query("SELECT * FROM categories", []);
    }

    public function getById($id)
    {
        return $this->query_unique("SELECT * FROM categories WHERE id = :id", ['id' => $id]);
    }

    public function update($id, $name)
    {
        return $this->execute(
            "UPDATE categories SET name = :name WHERE id = :id",
            ['name' => $name, 'id' => $id]
        );
    }

    public function delete($id)
    {
        return $this->execute("DELETE FROM categories WHERE id = :id", ['id' => $id]);
    }
}
