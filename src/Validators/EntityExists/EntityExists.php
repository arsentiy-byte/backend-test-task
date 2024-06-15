<?php declare(strict_types=1);

namespace App\Validators\EntityExists;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class EntityExists extends Constraint
{
    /**
     * @param string|null $entity
     * @param string|null $property
     * @param string|null $message
     * @param mixed|null $options
     * @param list<string>|null $groups
     * @param mixed|null $payload
     */
    #[HasNamedArguments]
    public function __construct(
        public ?string $entity = null,
        public ?string $property = 'id',
        public ?string $message = 'Entity "%entity%" with property "%property%": "%value%" does not exist',
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct($options, $groups, $payload);
    }
}
