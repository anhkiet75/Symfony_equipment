<?php

namespace App\Repository;

use App\Entity\Equipment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Equipment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Equipment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipment[]    findAll()
 * @method Equipment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipment::class);
    }

    public function getAll() {
        return $this->findAll();
    }

    public function findOne($id) {
        return $this->find($id);
    }

    public function findByID($id) {
        return $this->findBy(['category' => $id]);
        // return $this->find($id) ;
    }

    public function getHistory(Equipment $entity) {
        return $entity->getAssigns();
    }

    public function add(Equipment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Equipment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function update(Equipment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function delete($id, bool $flush = false): void
    {
        $equipment = $this->find($id); 
        $this->getEntityManager()->remove($equipment);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function setStatus(Equipment $entity,$status) {
        $entity->setStatus($status);
        $this->getEntityManager()->flush();
    }

    public function search($value) {
        if (empty($value)) return $this->findAll();
        return $this->createQueryBuilder('e')
        ->where('e.name LIKE :name')
        ->setParameter('name', '%'.$value.'%')
        ->orWhere('e.id LIKE :id')
        ->setParameter('id', '%'.$value.'%')
        ->getQuery()
        ->getResult();
        // return $this->findAll();
    }

    // /**
    //  * @return Equipment[] Returns an array of Equipment objects
    //  */

    /*
    public function findOneBySomeField($value): ?Equipment
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
