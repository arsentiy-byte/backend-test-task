<?php declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Entity\Product;
use App\Entity\Tax;
use App\Entity\User;
use App\Entity\Voucher;
use App\Enums\PaymentProcessor;
use App\Tests\Functional\FunctionalTestCase;
use Faker\Factory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PurchaseActionTest extends FunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->authenticateUser();
    }

    public function testPurchase(): void
    {
        $faker = Factory::create();

        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['title' => 'Iphone']);
        /** @var Voucher $voucher */
        $voucher = $this->entityManager->getRepository(Voucher::class)->findOneBy(['code' => 'P10']);
        /** @var Tax $tax */
        $tax = $this->entityManager->getRepository(Tax::class)->findOneBy(['code' => 'GR216746731']);
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'test@example.com']);
        /** @var PaymentProcessor $paymentProcessor */
        $paymentProcessor = $faker->randomElement(PaymentProcessor::cases());

        $this->client->request(Request::METHOD_POST, '/api/purchase', [
            'product' => $product->getId(),
            'taxNumber' => $tax->getCode(),
            'couponCode' => $voucher->getCode(),
            'paymentProcessor' => $paymentProcessor->value,
        ]);

        self::assertResponseIsSuccessful();
        $jsonResult = \json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('id', $jsonResult);
        self::assertSame($product->getId(), $jsonResult['product']['id']);
        self::assertSame($user->getId(), $jsonResult['client']['id']);
        self::assertSame($voucher->getCode(), $jsonResult['voucher']['code']);
        self::assertSame($tax->getCode(), $jsonResult['tax']['code']);
        self::assertSame($product->calculatePrice($tax, $voucher), $jsonResult['price']);
        self::assertArrayHasKey('createdAt', $jsonResult);
        self::assertArrayHasKey('updatedAt', $jsonResult);
        self::assertSame($paymentProcessor->value, $jsonResult['paymentProcessor']);
    }

    public function testInvalidPayload(): void
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['title' => 'Iphone']);
        /** @var Voucher $voucher */
        $voucher = $this->entityManager->getRepository(Voucher::class)->findOneBy(['code' => 'P10']);
        /** @var Tax $tax */
        $tax = $this->entityManager->getRepository(Tax::class)->findOneBy(['code' => 'GR216746731']);

        $this->client->request(Request::METHOD_POST, '/api/purchase', [
            'product' => $product->getId(),
            'taxNumber' => $tax->getCode(),
            'couponCode' => $voucher->getCode(),
            'paymentProcessor' => 'none',
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
