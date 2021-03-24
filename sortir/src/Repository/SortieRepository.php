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
            ->where('s.etat != 2')
            ->andWhere('s.etat != 7')
            ->getQuery()
            ->execute();
    }

    public function findSorties($site, $nom, $dateDebut, $dateCloture)
    {
        $query = $this->createQueryBuilder('s');
        if ($site != null && $site->getId() != null) {
            $query->andWhere('s.site = :id')
                ->setParameter(':id', $site->getId());
        }
        if ($nom != null) {
            $query->andWhere('s.nom like :nom')
                ->setParameter(':nom', '%' . $nom . '%');
        }
        if ($dateDebut != null) {
            $query->andWhere('s.dateDebut >= :dateDebut')
                ->setParameter(':dateDebut', $dateDebut);
        }
        if ($dateCloture != null) {
            $query->andWhere('s.dateCloture <= :dateCloture')
                ->setParameter(':dateCloture', $dateCloture);
        }
        $result = $query->getQuery()
            ->getResult();
        return $result;
    }
}
