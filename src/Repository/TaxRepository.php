<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tax;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tax>
 */
class TaxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tax::class);
    }

    /**
     * @param Tax $tax
     * @return void
     */
    public function save(Tax $tax): void
    {
        $this->getEntityManager()->persist($tax);
        $this->getEntityManager()->flush();
    }
}
