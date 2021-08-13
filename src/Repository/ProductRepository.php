<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Returns all Products per page
     * @param mixed $page
     * @param mixed $limit
     * @return void
     */
    public function getPaginatedAnnonces($page, $limit, $filters){
        $query = $this->createQueryBuilder('product');
            
        if($filters != null){
            $query->where('product.categories IN(:cats)')
                  ->setParameter(':cats', array_values($filters));
        }
            $query->orderBy('product.buying_date')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit);
            return $query->getQuery()->getResult();
    }

    /**
     * Returns number of Annonces
     * @return void
     */
    public function getTotalAnnonces(){
        $query = $this->createQueryBuilder('product')
            ->select('COUNT(product)');
        return $query->getQuery()->getSingleScalarResult();
    }
}
