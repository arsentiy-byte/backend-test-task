<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Voucher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Voucher>
 */
class VoucherRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voucher::class);
    }

    /**
     * @param Voucher $voucher
     * @return void
     */
    public function save(Voucher $voucher): void
    {
        $this->getEntityManager()->persist($voucher);
        $this->getEntityManager()->flush();
    }
}
