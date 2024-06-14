<?php declare(strict_types=1);

namespace App\Controller\Authentication;

use App\DTO\Authentication\RegistrationDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(path: '/api/register', name: 'api_register', methods: ['POST'])]
class RegistrationAction
{
    /**
     * @param UserRepository $repository
     * @param UserPasswordHasherInterface $passwordHasher
     * @param SerializerInterface $serializer
     */
    public function __construct(
        private readonly UserRepository $repository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly SerializerInterface $serializer,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(#[MapRequestPayload] RegistrationDTO $dto): JsonResponse
    {
        $user = User::create(
            $dto->email,
            ['ROLE_USER'],
            $dto->password
        );

        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

        $this->repository->save($user);

        $context = (new ObjectNormalizerContextBuilder())->withGroups(['api'])->toArray();

        return new JsonResponse(
            $this->serializer->normalize($user, null, $context), // @phpstan-ignore-line
            Response::HTTP_CREATED
        );
    }
}
