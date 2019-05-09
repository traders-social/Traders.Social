<?php

namespace App\Repository\Security;

use App\Entity\Security\Right;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Right|null find($id, $lockMode = null, $lockVersion = null)
 * @method Right|null findOneBy(array $criteria, array $orderBy = null)
 * @method Right[]    findAll()
 * @method Right[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RightRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Right::class);
    }

//    /**
//     * @return Rights[] Returns an array of Rights objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Rights
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
