<?php

declare(strict_types=1);

namespace WHInterviewTask;

use Symfony\Component\HttpFoundation\InputBag;

class Customer extends Controller
{
    /**
     * This method create a new customer to the database
     * @param $args
     * @return void
     */
    public function create(InputBag $args)
    {
        $params = $args->all();

        $name = $args->get('name');
        $email = $args->get('email');

        $conn = $this->get_db_connection();
        $query = 'SELECT * FROM myapp.customers';
        // prepare the query
        $stmt = $conn->prepare($query);
        $stmt->execute();

        // fetch results
        $result = $stmt->fetchAll();

        // check if the customer already exists
        for ($i = 0; $i < count($result); $i++) {
            if ($result[$i]['email'] == $params['email']) {
                echo 'Customer already exists';
                return;
            }
        }

        // insert the page
        $query = 'INSERT INTO myapp.customers (name, email) VALUES (:name, :email)';
        $stmt = $conn->prepare($query);
        $stmt->execute([
            'name' => $name,
            'email' =>$email
        ]);
    }

    /**
     * Customers list
     *
     * @return void
     */
    public function customers_list()
    {
        $id = $_GET['id'];
        $result = $this->get_db_connection()->query("SELECT * FROM myapp.customers WHERE id = $id");
        return $result->fetchAll();
    }

}
