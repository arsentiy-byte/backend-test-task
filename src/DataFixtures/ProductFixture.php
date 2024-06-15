<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $products = [
            ['title' => 'Iphone', 'price' => 100.0],
            ['title' => 'Наушники', 'price' => 20.0],
            ['title' => 'Чехол', 'price' => 10.0],
        ];

        foreach ($products as $product) {
            $entity = Product::create($product['title'], $product['price']);
            $manager->persist($entity);
        }

        $manager->flush();
    }
}
