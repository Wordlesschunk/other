<?php

namespace App\Model;

class JobApplication
{
    public function __construct(
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?int $yearsOfExperience = null,
        public ?string $currentRole = null,
        public array $skills = [],
        public ?\DateTimeInterface $startDate = null,
        public ?string $workType = null,
        public ?float $salaryExpectation = null,
        public ?string $remotePreference = null,
        public string $currentStep = 'personal_info',
    ) {
    }
}
