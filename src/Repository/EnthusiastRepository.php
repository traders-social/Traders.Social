<?php

namespace App\Repository;

use App\Entity\Enthusiast;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Enthusiast|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enthusiast|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enthusiast[]    findAll()
 * @method Enthusiast[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnthusiastRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Enthusiast::class);
    }

//    /**
//     * @return Enthousiast[] Returns an array of Enthousiast objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Enthousiast
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
