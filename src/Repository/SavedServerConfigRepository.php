<?php

namespace App\Repository;

use App\Entity\SavedServerConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SavedServerConfig>
 */
class SavedServerConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SavedServerConfig::class);
    }

    /** @return SavedServerConfig[] */
    public function findByTemplateId(string $templateId): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.template', 't')
            ->andWhere('t.id = :templateId')
            ->setParameter('templateId', $templateId)
            ->getQuery()
            ->getResult();
    }

    public function save(SavedServerConfig $config, bool $flush = true): void
    {
        $this->getEntityManager()->persist($config);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SavedServerConfig $config, bool $flush = true): void
    {
        $this->getEntityManager()->remove($config);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
