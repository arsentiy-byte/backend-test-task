<?php declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Entity\Product;
use App\Entity\Tax;
use App\Entity\Voucher;
use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PriceCalculationActionTest extends FunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->authenticateUser();
    }

    public function testPriceCalculation(): void
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['title' => 'Iphone']);
        /** @var Voucher $voucher */
        $voucher = $this->entityManager->getRepository(Voucher::class)->findOneBy(['code' => 'P10']);
        /** @var Tax $tax */
        $tax = $this->entityManager->getRepository(Tax::class)->findOneBy(['code' => 'GR216746731']);

        $this->client->request(Request::METHOD_POST, '/api/calculate-price', [
            'product' => $product->getId(),
            'taxNumber' => $tax->getCode(),
            'couponCode' => $voucher->getCode(),
        ]);

        self::assertResponseIsSuccessful();
        $jsonResult = \json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertEquals($product->calculatePrice($tax, $voucher), $jsonResult['price']);
    }

    public function testInvalidPayload(): void
    {
        $this->client->request(Request::METHOD_POST, '/api/calculate-price', [
            'product' => -1,
            'taxNumber' => 'none',
            'couponCode' => 'none',
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $jsonResult = \json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('Invalid payload', $jsonResult['message']);
        self::assertArrayHasKey('product', $jsonResult['errors']);
        self::assertArrayHasKey('taxNumber', $jsonResult['errors']);
        self::assertArrayHasKey('couponCode', $jsonResult['errors']);
    }
}
