<?php

namespace App\Repository;

use App\Entity\Produits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produits>
 *
 * @method Produits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produits[]    findAll()
 * @method Produits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produits::class);
    }


    /**
     * @param $category
     * @return Produits[]
     */
    public function produitsByCategory($category): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.categorie = :category_id')
            ->setParameter('category_id', $category)
            ->getQuery()
            ->getResult();
    }

    /**
     * Undocumented function
     *
     * @param [type] $distributeur
     * @return array
     */
    public function produitsByDistributeur($distributeur): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.distributeur', 'd')
            ->andWhere('d.id = :distributeur_id')
            ->setParameter('distributeur_id', $distributeur)
            ->getQuery()
            ->getResult();
    }
}
