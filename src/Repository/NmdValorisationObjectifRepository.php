<?php

namespace App\Repository;

use App\Entity\NmdValorisationObjectif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NmdValorisationObjectif|null find($id, $lockMode = null, $lockVersion = null)
 * @method NmdValorisationObjectif|null findOneBy(array $criteria, array $orderBy = null)
 * @method NmdValorisationObjectif[]    findAll()
 * @method NmdValorisationObjectif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NmdValorisationObjectifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NmdValorisationObjectif::class);
    }

    public function valorisationByVolume($user_id,$prct, $product_id) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * FROM `nmd_valorisation_objectif` V 
                WHERE id_user=$user_id AND ($prct >= V.`step_start` AND $prct < V.`step_end`) AND V.product_id= $product_id";

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetch();

    }

    public function valorisationSup($user_id, $product_id) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * FROM `nmd_valorisation_objectif`  WHERE id_user=$user_id  AND product_id= $product_id ORDER BY step_end DESC LIMIT 1 ";

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetch();

    }

    // /**
    //  * @return NmdValorisationObjectif[] Returns an array of NmdValorisationObjectif objects
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
    public function findOneBySomeField($value): ?NmdValorisationObjectif
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
