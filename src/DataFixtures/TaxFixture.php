<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Tax;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TaxFixture extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $taxes = [
            [
                'code' => sprintf('DE%s', $faker->numerify('#########')),
                'country' => 'Германия',
                'value' => 19,
            ],
            [
                'code' => sprintf('IT%s', $faker->numerify('###########')),
                'country' => 'Италия',
                'value' => 22,
            ],
            [
                'code' => sprintf('FRYY%s', $faker->numerify('#########')),
                'country' => 'Франция',
                'value' => 20,
            ],
            [
                'code' => sprintf('GR%s', $faker->numerify('#########')),
                'country' => 'Греция',
                'value' => 24,
            ]
        ];

        foreach ($taxes as $tax) {
            $entity = Tax::create($tax['code'], $tax['country'], $tax['value']);
            $manager->persist($entity);
        }

        $manager->flush();
    }
}
