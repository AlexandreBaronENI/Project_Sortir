<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function findAll()
    {
        return $this->createQueryBuilder('s')
            ->where('s.etat != 0')
            ->getQuery()
            ->execute();
    }

    public function findSorties($site, $nom, $dateDebut, $dateCloture)
    {
        $query = $this->createQueryBuilder('s');
        if($site != null && $site->getId() != null){
            //print_r("j'ai un site");
            $query->andWhere('s.id = :id')
                ->setParameter(':id', $site->getId());
        }
        if($nom != null){
            //print_r("j'ai un nom");
            $query->andWhere('s.nom like :nom')
                ->setParameter(':nom', '%'.$nom.'%');
        }
        if($dateDebut != null){
            //print_r("j'ai une date de debut");
            $query->andWhere('s.dateDebut >= :dateDebut')
                ->setParameter(':dateDebut', $dateDebut);
        }
        if($dateCloture != null){
            //print_r("j'ai une date de cloture");
            $query->andWhere('s.dateCloture <= :dateCloture')
                ->setParameter(':dateCloture', $dateCloture);
        }
        $result =  $query->getQuery()
            ->getResult();
        return $result;
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
