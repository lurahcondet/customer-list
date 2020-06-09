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

class Add
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
            $this->addCustomer();
            $customerList = $this->customer->read(Customer::SOURCE_DB);
            $this->response->output($customerList);
        } catch (\Exception $e) {
            $this->response->output([], 400, 'failed', $e->getMessage());
        }
    }

    /**
     * add customer
     */
    protected function addCustomer()
    {
        $customerData = $this->input->getPost();

        if ($this->input->validate($customerData)) {
            $this->customer->add($customerData);
        }
    }
}

$add = new Add();
$add->execute();
