<?php

namespace App\Api\ApiResponse;

use Illuminate\Http\JsonResponse;

class ApiResponseBuilder
{
    private ApiResponse $response;

    public function __construct()
    {
        $this->response = new ApiResponse();
    }

    public function withMessage(string $message) : ApiResponseBuilder
    {
        $this->response->setMessage($message);
        return $this;
    }

    public function withData(mixed $data) : ApiResponseBuilder
    {
        $this->response->setData($data);
        return $this;
    }

    public function withStatus(int $status) : ApiResponseBuilder
    {
        $this->response->setStatus($status);
        return $this;
    }

    public function withErrors(array $errors) : ApiResponseBuilder
    {
        $this->response->setErrors($errors);
        return $this;
    }

    public function send() : JsonResponse
    {
        return $this->response->response();
    }
}
