<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\PriceCalculationDTO;
use App\Handlers\PriceCalculationHandler;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/api/calculate-price', name: 'calculate_price', methods: ['POST'])]
#[IsGranted('ROLE_USER')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Returns calculated price for product taking into account tax and coupon',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'price', type: 'float', example: '68.4')
        ],
        type: 'object'
    )
)]
#[OA\Tag(name: 'api')]
#[Security(name: 'Bearer')]
class PriceCalculationAction
{
    /**
     * @param PriceCalculationHandler $handler
     */
    public function __construct(
        private readonly PriceCalculationHandler $handler,
    ) {
    }

    /**
     * @param PriceCalculationDTO $dto
     * @return JsonResponse
     */
    public function __invoke(#[MapRequestPayload] PriceCalculationDTO $dto): JsonResponse
    {
        $result = $this->handler->handle($dto);

        return new JsonResponse([
            'price' => $result->calculatedPrice,
        ], Response::HTTP_OK);
    }
}
