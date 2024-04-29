<?php

namespace App\Repository;

use App\Entity\Allergie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Allergie>
 *
 * @method Allergie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Allergie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Allergie[]    findAll()
 * @method Allergie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AllergieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Allergie::class);
    }

//    /**
//     * @return Allergie[] Returns an array of Allergie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Allergie
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function orderByNom()
{
    return $this->createQueryBuilder('s')
        ->orderBy('s.nom', 'ASC')
        ->getQuery()->getResult();
}
public function findAllWithInformationCount(): array
{
    return $this->createQueryBuilder('a')
        ->leftJoin('a.informations', 'i') // i est un alias pour InformationEducatif
        ->select('a.nom AS allergie_nom, COUNT(i.idinformation) AS information_count')
        ->groupBy('a.id')
        ->getQuery()
        ->getResult();
}


}
