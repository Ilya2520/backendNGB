<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

//    /**
//     * @return Transaction[] Returns an array of Transaction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
//    public function findAll(): ?array
//    {
//        return $this->createQueryBuilder('t')
//            ->select('t.id', 't.Amount', 't.Status', 't.Message', 't.')
//            ->getQuery()
//            ->getResult(AbstractQuery::HYDRATE_ARRAY);
//    }
    
    public function findBySomeField(string $fieldName, $value): ?array
    {
        return $this->createQueryBuilder('t')
            ->andWhere("t.$fieldName = :val")
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
    }
}
