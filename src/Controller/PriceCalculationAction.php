<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\PriceCalculationDTO;
use App\Handlers\PriceCalculationHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/api/calculate-price', name: 'calculate_price', methods: ['POST'])]
#[IsGranted('ROLE_USER')]
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
