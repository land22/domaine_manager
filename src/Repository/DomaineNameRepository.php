<?php

namespace App\Repository;

use App\Entity\DomaineName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DomaineName|null find($id, $lockMode = null, $lockVersion = null)
 * @method DomaineName|null findOneBy(array $criteria, array $orderBy = null)
 * @method DomaineName[]    findAll()
 * @method DomaineName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DomaineNameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DomaineName::class);
    }

    // /**
    //  * @return DomaineName[] Returns an array of DomaineName objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DomaineName
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
