<?php

declare(strict_types=1);

namespace WHInterviewTask;

use Symfony\Component\HttpFoundation\InputBag;

class Order extends Controller
{
    /**
     * @param InputBag $args
     * @return void
     */
    function get_order_details($args)
    {
        $order_id = $args->get('order_id');
        $query = "SELECT * FROM myapp.orders WHERE id = $order_id";
        $result = $this->get_db_connection()->query($query);

        if ($result && $result->rowCount() > 0) {
            $order = $result->fetch(\PDO::FETCH_ASSOC);
            $customerId = $order['customer_id'];

            $query = "SELECT * FROM myapp.customers WHERE id = $customerId";
            $result = $this->get_db_connection()->query($query);

            if ($result && $result->rowCount() > 0) {
                $customer = $result->fetch(\PDO::FETCH_ASSOC);

                echo "Order Details:\n";
                echo "Order ID: " . $order['id'] . "\n";
                echo "Customer Name: " . $customer['name'] . "\n";
                echo "Order Date: " . $order['order_date'] . "\n";
                echo "Total Amount: " . $order['total_amount'] . "\n";
            } else {
                echo "Customer not found.";
            }
        } else {
            echo "Order not found.";
        }
    }

    /**
     * @param InputBag $args
     * @return false|mixed
     */
    function create_order($args)
    {
        $customerId = $args->get('customer_id');
        $totalAmount = $args->get('total_amount');
        $query = "INSERT INTO myapp.orders (customer_id, total_amount) VALUES ($customerId, $totalAmount)";
        $stmt = $this->get_db_connection()->query($query);

        if ($stmt->execute()) {
            $orderId = $this->get_db_connection()->lastInsertId();
            return $orderId;
        } else {
            return false;
        }
    }

    function calculate_order_total()
    {
        $query = "SELECT * FROM myapp.orders";
        $total = 0;
        $result = $this->get_db_connection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($result as $item) {
            if (isset($item['total_amount'])) {
                $total += $item['total_amount'];
            }
        }
        return $total;
    }
}
