<?php declare(strict_types=1);

namespace App\Tests\Functional\Controller\Authentication;

use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class RegistrationActionTest extends WebTestCase
{
    /**
     * @throws \JsonException
     */
    public function testUserRegistration(): void
    {
        $faker = Factory::create();

        $email = $faker->email;

        $client = static::createClient();
        $client->request(Request::METHOD_POST, '/api/register', [
            'email' => $email,
            'password' => $faker->password(12),
        ]);

        self::assertResponseIsSuccessful();

        $jsonResult = \json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertNotEmpty($jsonResult['id']);
        self::assertEquals($email, $jsonResult['email']);
        self::assertEquals(['ROLE_USER'], $jsonResult['roles']);
    }
}
