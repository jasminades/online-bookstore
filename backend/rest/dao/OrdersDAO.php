<?php

require_once __DIR__ . "/BaseDao.php";

class OrdersDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("orders");
    }


    public function create($user_id, $total_price, $status = 'pending', $book_id, $order_date, $quantity)
    {
        try {
            $order = [
                'user_id' => $user_id,
                'total_price' => $total_price,
                'status' => $status,
                'book_id' => $book_id,
                'order_date' => $order_date,
                'quantity' => $quantity,
                'created_at' => date('Y-m-d H:i:s')
            ];
            return $this->insert("orders", $order);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "Error: Duplicate entry.";
            } else {
                echo "Error: " . $e->getMessage();
            }
        }
    }


    public function get_all()
    {
        return $this->query("SELECT * FROM orders ORDER BY created_at DESC", []);
    }

    public function getAllByUser($user_id)
    {
        return $this->query(
            "SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC",
            ['user_id' => $user_id]
        );
    }

    public function get_by_id($id)
    {
        return $this->query_unique("SELECT * FROM orders WHERE id = :id", ['id' => $id]);
    }

    public function updateOrder($id, $total_price, $status)
    {
        return $this->execute(
            "UPDATE orders SET total_price = :total_price, status = :status WHERE id = :id",
            ['total_price' => $total_price, 'status' => $status, 'id' => $id]
        );
    }

    public function updatePartial($id, $fields)
    {
        $setClause = implode(", ", array_map(fn($col) => "$col = :$col", array_keys($fields)));
        $fields['id'] = $id;
        $sql = "UPDATE orders SET $setClause WHERE id = :id";
        return $this->execute($sql, $fields);
    }

    public function deleteOrder($id)
    {
        return $this->delete($id);

    }

}

