<?php

/**
 * Customer List
 *
 * @author Didi Kusnadi<jalapro08@gmail.com>
 *
 */
namespace CustomerKit;

class Response
{
    /**
     * sent http response
     * @param  array   $output
     * @param  integer $code
     * @param  string  $response
     * @param  string|null  $message
     * @return void
     */
    public function output($output = [], $code = 200, $response = 'success', $message = null)
    {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => [
                'code' => $code,
                'response' => $response,
                'message' => $message
            ],
            'result' => $output
        ]);
    }
}
