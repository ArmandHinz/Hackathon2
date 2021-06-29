<?php

namespace App\Repository;

use App\Entity\MessageChanel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MessageChanel|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageChanel|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageChanel[]    findAll()
 * @method MessageChanel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageChanelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageChanel::class);
    }

    // /**
    //  * @return MessageChanel[] Returns an array of MessageChanel objects
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
    public function findOneBySomeField($value): ?MessageChanel
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
