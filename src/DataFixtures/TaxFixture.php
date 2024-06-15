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
                'code' => 'DE795581124',
                'country' => 'Германия',
                'value' => 19,
            ],
            [
                'code' => 'IT87059898280',
                'country' => 'Италия',
                'value' => 22,
            ],
            [
                'code' => 'FRYY610521164',
                'country' => 'Франция',
                'value' => 20,
            ],
            [
                'code' => 'GR216746731',
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
