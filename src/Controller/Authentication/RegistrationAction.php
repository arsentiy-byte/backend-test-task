<?php declare(strict_types=1);

namespace App\Controller\Authentication;

use App\DTO\Authentication\RegistrationDTO;
use App\Entity\User;
use App\Handlers\Authentication\RegistrationHandler;
use App\Helpers\ObjectNormalizer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/register', name: 'api_register', methods: ['POST'])]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Returns created user after registration',
    content: new Model(type: User::class, groups: ['user_list'])
)]
#[OA\Tag(name: 'api')]
class RegistrationAction
{
    /**
     * @param RegistrationHandler $handler
     * @param ObjectNormalizer $normalizer
     */
    public function __construct(
        private readonly RegistrationHandler $handler,
        private readonly ObjectNormalizer $normalizer,
    ) {
    }

    /**
     * @param RegistrationDTO $dto
     * @return JsonResponse
     */
    public function __invoke(#[MapRequestPayload] RegistrationDTO $dto): JsonResponse
    {
        $user = $this->handler->handle($dto);

        return new JsonResponse(
            $this->normalizer->normalizeUsingGroups($user, ['user_list']),
            Response::HTTP_CREATED
        );
    }
}
