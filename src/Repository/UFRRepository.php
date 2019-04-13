<?php

namespace App\Repository;

use App\Entity\UFR;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UFR|null find($id, $lockMode = null, $lockVersion = null)
 * @method UFR|null findOneBy(array $criteria, array $orderBy = null)
 * @method UFR[]    findAll()
 * @method UFR[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UFRRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UFR::class);
    }

    // /**
    //  * @return UFR[] Returns an array of UFR objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UFR
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
