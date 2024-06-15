<?php declare(strict_types=1);

namespace App\CustomExceptionListener;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationFailedErrorsBuilder
{
    /**
     * @param ConstraintViolationListInterface $list
     * @return array<string, string>
     */
    public function build(ConstraintViolationListInterface $list): array
    {
        $errors = [];

        foreach ($list as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }
}
