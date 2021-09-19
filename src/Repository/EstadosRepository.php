<?php

namespace App\Repository;

use App\Entity\Estados;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Estados|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estados|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estados[]    findAll()
 * @method Estados[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Estados::class);
    }

    // /**
    //  * @return Estados[] Returns an array of Estados objects
    //  */
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
    public function findOneBySomeField($value): ?Estados
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
