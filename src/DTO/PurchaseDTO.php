<?php declare(strict_types=1);

namespace App\DTO;

use App\Enums\PaymentProcessor;
use Symfony\Component\Validator\Constraints as Assert;

class PurchaseDTO
{
    /**
     * @param string $paymentProcessor
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(choices: [PaymentProcessor::PAYPAL->value, PaymentProcessor::STRIPE->value])]
        public string $paymentProcessor,
    ) {
    }
}
