<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Voucher;
use App\Enums\DiscountType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VoucherFixture extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $vouchers = [
            ['code' => 'P10', 'discountType' => DiscountType::PERCENTAGE, 'discount' => 10],
            ['code' => 'P100', 'discountType' => DiscountType::PERCENTAGE, 'discount' => 100],
            ['code' => 'F50', 'discountType' => DiscountType::FIXED, 'discount' => 50],
        ];

        foreach ($vouchers as $voucher) {
            $entity = Voucher::create(
                $voucher['code'],
                $voucher['discountType'],
                $voucher['discount'],
            );

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
