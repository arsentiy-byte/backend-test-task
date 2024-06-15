<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\PriceCalculationDTO;
use App\DTO\PurchaseDTO;
use App\Entity\Order;
use App\Entity\User;
use App\Exceptions\PaymentProcessorException;
use App\Handlers\PurchaseHandler;
use App\Helpers\ObjectNormalizer;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security as OASecurity;
use OpenApi\Attributes as OA;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/api/purchase', name: 'purchase', methods: ['POST'])]
#[IsGranted('ROLE_USER')]
#[OA\RequestBody(
    content: new OA\JsonContent(
        required: ['product', 'taxNumber', 'paymentProcessor'],
        properties: [
            new OA\Property(property: 'product', type: 'integer'),
            new OA\Property(property: 'taxNumber', type: 'string'),
            new OA\Property(property: 'couponCode', type: 'string'),
            new OA\Property(property: 'paymentProcessor', type: 'string'),
        ],
        type: 'object',
    ),
)]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Returns purchased order',
    content: new Model(type: Order::class, groups: ['order_list'])
)]
#[OA\Tag(name: 'api')]
#[OASecurity(name: 'Bearer')]
class PurchaseAction
{
    /**
     * @param PurchaseHandler $handler
     * @param Security $security
     * @param ObjectNormalizer $normalizer
     */
    public function __construct(
        private readonly PurchaseHandler $handler,
        private readonly Security $security,
        private readonly ObjectNormalizer $normalizer,
    ) {
    }

    /**
     * @param PriceCalculationDTO $priceCalculationDTO
     * @param PurchaseDTO $dto
     * @return JsonResponse
     * @throws PaymentProcessorException
     */
    public function __invoke(
        #[MapRequestPayload]
        PriceCalculationDTO $priceCalculationDTO,
        #[MapRequestPayload]
        PurchaseDTO $dto,
    ): JsonResponse {
        /** @var User $user */
        $user = $this->security->getUser();
        $order = $this->handler->handle($priceCalculationDTO, $dto, $user);

        return new JsonResponse(
            $this->normalizer->normalizeUsingGroups($order, ['order_list']),
            Response::HTTP_CREATED
        );
    }
}
