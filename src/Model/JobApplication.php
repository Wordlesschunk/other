<?php

namespace App\Model;

class JobApplication
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $message = null,
        public string $currentStep = 'step1',
    ) {
    }
}
