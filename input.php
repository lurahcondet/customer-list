<?php

/**
 * Customer List
 *
 * @author Didi Kusnadi<jalapro08@gmail.com>
 *
 */
namespace CustomerKit;

class Input
{
    
    /**
     * get post params
     * @return array
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function getPost()
    {
        $request = [];
        if (count($_POST)) {
            foreach ($_POST as $key => $value) {
                if ($key == 'password') {
                    $request[$key] = $value;
                }
                $request[$key] = strip_tags($value);
            }
        }
        return $request;
    }

    /**
     * validate input
     * @param  array $data
     * @return boolean
     * @throws Exception
     */
    public function validate($data)
    {
        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("invalid email");
        }
        
        return true;
    }
}
