<?php declare(strict_types=1);

namespace App\Tests\Unit\Handlers;

use App\DTO\Authentication\RegistrationDTO;
use App\Handlers\Authentication\RegistrationHandler;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RegistrationHandlerTest extends KernelTestCase
{
    private RegistrationHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->handler = self::getContainer()->get(RegistrationHandler::class);
    }

    public function testRegistration(): void
    {
        $faker = Factory::create();
        $dto = new RegistrationDTO($faker->email, $faker->password(10));

        $user = $this->handler->handle($dto);

        self::assertNotNull($user->getId());
        self::assertSame($dto->email, $user->getEmail());
    }
}
