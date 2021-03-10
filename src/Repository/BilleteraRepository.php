<?php

namespace App\Repository;

use App\Entity\Billetera;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Billetera|null find($id, $lockMode = null, $lockVersion = null)
 * @method Billetera|null findOneBy(array $criteria, array $orderBy = null)
 * @method Billetera[]    findAll()
 * @method Billetera[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BilleteraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Billetera::class);
    }

    // /**
    //  * @return Billetera[] Returns an array of Billetera objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Billetera
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
