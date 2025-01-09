<?php

class ApiKeyValidator
{
    private $apiKey = "cestlacle";

    public function isValidKey($token)
    {
        return $token === $this->apiKey;
    }
}
