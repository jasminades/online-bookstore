<?php

require_once './rest/dao/OrdersDAO.php';
require_once './rest/dao/UsersDAO.php';
require_once './rest/services/BaseService.php';

class OrdersService extends BaseService
{
    private $usersDAO;
    private $ordersDAO;

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELED = 'canceled';

    public function __construct()
    {
        $this->ordersDAO = new OrdersDAO(); 
        parent::__construct($this->ordersDAO);
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

        return $this->ordersDAO->create(
            $data['user_id'],
            $data['total_price'],
            $data['status'] ?? 'pending',
            $data['book_id'],
            $data['order_date'],
            $data['quantity']
        );
    }

    public function updateOrder($id, $data)
    {
        $this->validateOrderData($data, true);
        return $this->update($data, $id); 
    }

   public function deleteOrder($orderId) {
        return $this->ordersDAO->delete($orderId);
    }
}
