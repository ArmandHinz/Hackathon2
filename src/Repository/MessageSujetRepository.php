<?php

namespace App\Repository;

use App\Entity\MessageSujet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MessageSujet|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageSujet|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageSujet[]    findAll()
 * @method MessageSujet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageSujetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageSujet::class);
    }

    // /**
    //  * @return MessageSujet[] Returns an array of MessageSujet objects
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
    public function findOneBySomeField($value): ?MessageSujet
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
