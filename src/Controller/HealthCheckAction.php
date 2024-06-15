<?php declare(strict_types=1);

namespace App\Controller;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/health-check', name: 'health_check', methods: ['GET'])]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Checks for an api works',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'message', type: 'string', example: 'Success')
        ],
        type: 'object'
    )
)]
#[OA\Tag(name: 'base')]
class HealthCheckAction
{
    /**
     * @return Response
     */
    public function __invoke(): Response
    {
        return new JsonResponse(['message' => 'Success']);
    }
}
