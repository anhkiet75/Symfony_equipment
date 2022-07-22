<?php

namespace App\Repository;

use App\Entity\Assign;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Assign|null find($id, $lockMode = null, $lockVersion = null)
 * @method Assign|null findOneBy(array $criteria, array $orderBy = null)
 * @method Assign[]    findAll()
 * @method Assign[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssignRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Assign::class);
    }

    public function store(Assign $entity) {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    // /**
    //  * @return Assign[] Returns an array of Assign objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Assign
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
