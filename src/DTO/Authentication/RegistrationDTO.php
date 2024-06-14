<?php declare(strict_types=1);

namespace App\DTO\Authentication;

use Symfony\Component\Validator\Constraints as Assert;

class RegistrationDTO
{
    /**
     * @param string $email
     * @param string $password
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public readonly string $email,
        #[Assert\NotBlank]
        #[Assert\PasswordStrength([
            'minScore' => Assert\PasswordStrength::STRENGTH_WEAK,
        ])]
        public readonly string $password,
    ) {
    }
}
