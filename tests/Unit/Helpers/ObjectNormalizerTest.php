<?php declare(strict_types=1);

namespace App\Tests\Unit\Helpers;

use App\Entity\User;
use App\Helpers\ObjectNormalizer;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ObjectNormalizerTest extends KernelTestCase
{
    private ObjectNormalizer $normalizer;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $this->normalizer = self::getContainer()->get(ObjectNormalizer::class);
    }

    public function testNormalizeUsingGroups(): void
    {
        $faker = Factory::create();
        $user = User::create($faker->email, ['ROLE_USER'], $faker->password(10));

        $normalizedUser = $this->normalizer->normalizeUsingGroups($user, ['user_list']);

        self::assertIsArray($normalizedUser);
        self::assertArrayHasKey('id', $normalizedUser);
        self::assertArrayHasKey('email', $normalizedUser);
        self::assertArrayHasKey('roles', $normalizedUser);
    }
}
