<?php

namespace App\Helpers;

class InfureResponse
{
    private $response;

    private bool $hasError = false;
    private ?string $errorMessage = null;
    private ?string $errorCode = null;

    public function __construct(string $response)
    {
        $response = json_decode($response, true);

        if (isset($response['error'])) {
            $error = $response['error'];
            $this->hasError = true;
            $this->errorMessage = $error['message'];
            $this->errorCode = $error['code'];
        }

        $this->response = (isset($response['result'])) ? $response['result'] : $response;
    }

    public function hasError(): bool
    {
        return $this->hasError === true;
    }

    public function __toString(): string
    {
        if ($this->hasError()) {
            return $this->getErrorCode() . ': ' . $this->getErrorMessage();
        }

        $response = $this->getResponse();
        return is_array($response) ? json_encode($response) : $response;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getErrorCode(): ?int
    {
        return $this->errorCode;
    }

    /**
     * @return array|string
     */
    public function getResponse()
    {
        return $this->response;
    }
}
