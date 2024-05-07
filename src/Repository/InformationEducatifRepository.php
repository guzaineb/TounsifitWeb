<?php

namespace App\Repository;

use App\Entity\InformationEducatif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InformationEducatif>
 *
 * @method InformationEducatif|null find($id, $lockMode = null, $lockVersion = null)
 * @method InformationEducatif|null findOneBy(array $criteria, array $orderBy = null)
 * @method InformationEducatif[]    findAll()
 * @method InformationEducatif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InformationEducatifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InformationEducatif::class);
    }

//    /**
//     * @return InformationEducatif[] Returns an array of InformationEducatif objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InformationEducatif
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
