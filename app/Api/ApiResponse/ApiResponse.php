<?php

namespace App\Api\ApiResponse;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    private string $message = "The operation was completed successfully.";

    private mixed $data = [];

    private int $status = 200;

    private array $errors = [];

    /**
     * @param string $message
     */
    public function setMessage(string $message) : void
    {
        $this->message = $message;
    }

    /**
     * @param mixed $data
     */
    public function setData(mixed $data) : void
    {
        $this->data = $data;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status) : void
    {
        $this->status = $status;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors) : void
    {
        $this->errors = $errors;
    }

    public function response() : JsonResponse
    {
        $body = [];
        $body['message'] = $this->message;
        $body['data'] = $this->data;
        $body['errors'] = $this->errors;
        return response()->json($body, $this->status);
    }
}
