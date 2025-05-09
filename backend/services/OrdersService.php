<?php

require_once './dao/OrdersDAO.php';
require_once './dao/UsersDAO.php';

class OrdersService {
    private $ordersDAO;
    private $usersDAO;

    public function __construct() {
        $this->ordersDAO = new OrdersDAO(); 
        $this->usersDAO = new UsersDAO();  
    }

    public function getAllOrders() {
        return $this->ordersDAO->getAll(); 
    }

    public function getOrdersByUser($user_id) {
        return $this->ordersDAO->getAllByUser($user_id); 
    }

    public function getOrderById($id) {
        $order = $this->ordersDAO->getById($id); 
        if (!$order) {
            throw new Exception("Order not found");
        }
        return $order;
    }

   
    private function validateOrderData($data, $isUpdate = false) {
        $errors = [];

       
        if (!isset($data['user_id']) || !$this->usersDAO->getById($data['user_id'])) {
            $errors[] = "Invalid or non-existent user.";
        }

        if (!isset($data['total_price']) || !is_numeric($data['total_price']) || $data['total_price'] <= 0) {
            $errors[] = "Total price must be a positive number.";
        }

        if (isset($data['status']) && !in_array($data['status'], ['pending', 'completed', 'canceled'])) {
            $errors[] = "Invalid order status. Allowed values: 'pending', 'completed', 'canceled'.";
        }

        if (!empty($errors)) {
            throw new Exception(implode(" ", $errors));
        }
    }

    public function createOrder($data) {
        $this->validateOrderData($data);
        return $this->ordersDAO->create($data['user_id'], $data['total_price'], $data['status'] ?? 'pending');
    }

    public function updateOrder($id, $data) {
        $this->validateOrderData($data, true);
        $updated = $this->ordersDAO->update($id, $data['total_price'], $data['status']);

        if (!$updated) {
            throw new Exception("Order not found");
        }
        return $updated;
    }

    public function deleteOrder($id) {
        $deleted = $this->ordersDAO->delete($id);
        if (!$deleted) {
            throw new Exception("Order not found");
        }
        return $deleted;
    }
}
