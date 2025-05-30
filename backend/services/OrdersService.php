<?php

require_once './dao/OrdersDAO.php';
require_once './dao/UsersDAO.php';
require_once './services/BaseService.php';

class OrdersService extends BaseService
{
    private $usersDAO;

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELED = 'canceled';

    public function __construct()
    {
        $ordersDAO = new OrdersDAO();
        parent::__construct($ordersDAO); 
        $this->usersDAO = new UsersDAO();
    }

    public function getAllOrders()
    {
        return $this->get_all();
    }

    public function getOrdersByUser($user_id)
    {
        return $this->dao->getAllByUser($user_id); 
    }

    public function getOrderById($id)
    {
        $order = $this->get_by_id($id); 

        if (!$order) {
            throw new Exception("Order not found");
        }

        return $order;
    }

    private function validateOrderData($data, $isUpdate = false)
    {
        $errors = [];

        if (!isset($data['user_id']) || !$this->usersDAO->get_by_id($data['user_id'])) {
            $errors[] = "Invalid or non-existent user.";
        }

        if (!isset($data['total_price']) || !is_numeric($data['total_price']) || $data['total_price'] <= 0) {
            $errors[] = "Total price must be a positive number.";
        }

        if (isset($data['status']) && !in_array($data['status'], [self::STATUS_PENDING, self::STATUS_COMPLETED, self::STATUS_CANCELED])) {
            $errors[] = "Invalid order status. Allowed values: 'pending', 'completed', 'canceled'.";
        }

        if (!empty($errors)) {
            throw new Exception(implode(" ", $errors));
        }
    }

    public function createOrder($data)
    {
        $this->validateOrderData($data);
        return $this->add($data); 
    }

    public function updateOrder($id, $data)
    {
        $this->validateOrderData($data, true);
        return $this->update($data, $id); 
    }

    public function deleteOrder($id)
    {
        return $this->delete($id); 
    }
}
