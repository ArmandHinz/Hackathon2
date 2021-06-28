<?php

namespace App\Repository;

use App\Entity\MessageProjet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MessageProjet|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageProjet|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageProjet[]    findAll()
 * @method MessageProjet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageProjetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageProjet::class);
    }

    // /**
    //  * @return MessageProjet[] Returns an array of MessageProjet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MessageProjet
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
