<?php

namespace App\Http\Response;

use Illuminate\Http\JsonResponse as JsResponse;

class JsonResponse extends JsResponse
{
    /**
     * Constructor.
     *
     * @param  mixed  $data
     * @param  int  $status
     * @param  array  $headers
     * @param  int  $options
     * @return void
     */
    public function __construct($data = null, $status = 200, $headers = [], $options = 0)
    {
        if (is_string($data)) {
            $json = json_decode($data);
            if (!empty($json)) {
                parent::__construct($json, $status, $headers);
            } else {
                parent::__construct($data, $status, $headers);
            }
        } else {
            parent::__construct($data, $status, $headers);
        }
    }
}
