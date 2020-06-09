<?php

/**
 * Customer List
 *
 * @author Didi Kusnadi<jalapro08@gmail.com>
 *
 */
namespace CustomerKit;

require_once('customer.php');
require_once('response.php');
require_once('input.php');

use CustomerKit\Customer;
use CustomerKit\Response;
use CustomerKit\Input;

class Get
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
        $this->response = new Response();
        $this->input = new Input();
    }

    /**
     * execute
     * @return mixed
     */
    public function execute()
    {
        try {
            $customerData = $this->input->getPost();
            $customerList = $this->customer->get();

            if (isset($customerData['id']) && $customerData['id']) {
                $customerId = (int)$customerData['id'];
                foreach ($customerList as $key => $item) {
                    if ($item['id'] != $customerId) {
                        unset($customerList[$key]);
                    }
                }
            }
            $this->response->output($customerList);
        } catch (\Exception $e) {
            $this->response->output([], 400, 'failed', $e->getMessage());
        }
    }
}

$get = new Get();
$get->execute();
