<?php declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use JsonException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class HealthCheckActionTest extends WebTestCase
{
    /**
     * @throws JsonException
     */
    public function testRequestRespondedSuccessfulResult(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/health-check');

        self::assertResponseIsSuccessful();
        $jsonResult = \json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertEquals('Success', $jsonResult['message']);
    }
}
