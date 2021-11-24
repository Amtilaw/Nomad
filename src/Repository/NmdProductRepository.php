<?php

namespace App\Repository;

use App\Entity\NmdProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NmdProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method NmdProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method NmdProduct[]    findAll()
 * @method NmdProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NmdProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NmdProduct::class);
    }

    public function allProducts() {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * FROM `nmd_product` ORDER BY `organization_category` DESC ";

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetchAllAssociative();

    }

      public function allProduct(){

      $conn = $this->getEntityManager()->getConnection();

      $sql =

          " SELECT *
          
        FROM `nmd_product` 
      ";

      $stmt = $conn->prepare($sql);

      // returns an array of arrays (i.e. a raw data set)
      return $stmt->executeQuery()->fetchAllAssociative();

  }

      public function productByCategory(){

      $conn = $this->getEntityManager()->getConnection();

      $sql =

        " SELECT COUNT(DISTINCT P.id) as total, C.name as name
          FROM nmd_product P, nmd_categorie_product C
          WHERE P.category_id = C.id
          GROUP BY C.name    
      ";

      $stmt = $conn->prepare($sql);

      // returns an array of arrays (i.e. a raw data set)
      return $stmt->executeQuery()->fetchAllAssociative();

  }

    // /**
    //  * @return NmdProduct[] Returns an array of NmdProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NmdProduct
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
