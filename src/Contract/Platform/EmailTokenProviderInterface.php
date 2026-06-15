<?php

// src/Contract/EmailTokenProviderInterface.php
namespace App\Contract\Platform;

interface EmailTokenProviderInterface
{
    public function getTokens(): array; // returns ['[token]' => 'value']
}