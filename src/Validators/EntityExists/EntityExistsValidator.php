<?php declare(strict_types=1);

namespace App\Validators\EntityExists;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class EntityExistsValidator extends ConstraintValidator
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (! $constraint instanceof EntityExists) {
            throw new \LogicException(\sprintf('You can only pass "%s" as constraint to "%s".', EntityExists::class, self::class));
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (empty($constraint->entity)) {
            throw new \LogicException(\sprintf('Must set "entity" on "%s" validator', EntityExists::class));
        }

        /** @phpstan-ignore-next-line */
        $data = $this->entityManager
            ->getRepository($constraint->entity)
            ->findOneBy([$constraint->property => $value]);

        if (null === $data) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%entity%', $constraint->entity)
                ->setParameter('%property%', $constraint->property)
                ->setParameter('%value%', (string) $value)
                ->addViolation();
        }
    }
}
