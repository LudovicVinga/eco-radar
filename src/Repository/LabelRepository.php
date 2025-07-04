<?php

namespace App\Repository;

use App\Entity\Label;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Label>
 */
class LabelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Label::class);
    
    }


    //    /**
    //     * @return Label[] Returns an array of Label objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Label
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

   public function countByCountry(): array
{
    return $this->createQueryBuilder('l')
        ->select('l.country as country, COUNT(l.id) as count')
        ->groupBy('l.country')
        ->getQuery()
        ->getResult();
}


 public function findByFilters(?string $pays, ?string $secteur, ?string $nomLabel): array
    {
        $qb = $this->createQueryBuilder('l');

        if ($pays && $pays !== '') {
            $qb->andWhere('l.country = :pays')
               ->setParameter('pays', $pays);
        }

        if ($secteur && $secteur !== '') {
            $qb->andWhere('l.sector = :secteur')
               ->setParameter('secteur', $secteur);
        }

        if ($nomLabel && $nomLabel !== '') {
            $qb->andWhere('l.name LIKE :nom')
               ->setParameter('nom', '%' . $nomLabel . '%');
        }

        return $qb->getQuery()->getResult();
    }

    public function countBySector(): array
{
    return $this->createQueryBuilder('l')
        ->select('l.sector as sector, COUNT(l.id) as count')
        ->groupBy('l.sector')
        ->getQuery()
        ->getResult();
}

public function countByCertification(): array
{
    return $this->createQueryBuilder('l')
        ->select('l.certified as certified, COUNT(l.id) as count')
        ->groupBy('l.certified')
        ->getQuery()
        ->getResult();
}


}
