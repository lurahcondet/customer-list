<?php

/**
 * Customer List
 *
 * @author Didi Kusnadi<jalapro08@gmail.com>
 *
 */
namespace CustomerKit;

require_once('customer.php');
require_once('input.php');
require_once('response.php');

use CustomerKit\Customer;
use CustomerKit\Input;
use CustomerKit\Response;

class Delete
{
    
    /**
     * @var Customer
     */
    protected $customer;

    /**
     * @var Input
     */
    protected $input;

    /**
     * @var Response
     */
    protected $response;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->customer = new Customer();
        $this->input = new Input();
        $this->response = new Response();
    }

    /**
     * execute
     * @return mixed
     */
    public function execute()
    {
        try {
            $this->deleteCustomer();
            $customerList = $this->customer->read(Customer::SOURCE_DB);
            $this->response->output($customerList);
        } catch (\Exception $e) {
            $this->response->output([], 400, 'failed', $e->getMessage());
        }
    }

    /**
     * delete customer
     */
    protected function deleteCustomer()
    {
        $customerData = $this->input->getPost();

        if (isset($customerData['id']) && $customerData['id']) {
            $customerId = (int)$customerData['id'];
            $this->customer->delete($customerId);
        }
    }
}

$delete = new Delete();
$delete->execute();
