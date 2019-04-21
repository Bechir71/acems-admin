<?php

namespace App\Repository;

use App\Entity\BalanceSheet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BalanceSheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method BalanceSheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method BalanceSheet[]    findAll()
 * @method BalanceSheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BalanceSheetRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BalanceSheet::class);
    }

    /**
     * @return BalanceSheet Returns a BalanceSheet object
     */
    public function getCurrent()
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?BalanceSheet
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
