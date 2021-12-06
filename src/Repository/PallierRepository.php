<?php

namespace App\Repository;

use App\Entity\Pallier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pallier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pallier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pallier[]    findAll()
 * @method Pallier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PallierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pallier::class);
    }

    public function pallierByVideo($videoId)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT DISTINCT id_pallier_id as id_pallier, pallier.timecode FROM `question`, `pallier` WHERE id_video_id = $videoId
        AND question.id_pallier_id = pallier.id ORDER BY `ordering` ASC ";
        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function allPallier()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * FROM `pallier`;";

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    // /**
    //  * @return Pallier[] Returns an array of Pallier objects
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
    public function findOneBySomeField($value): ?Pallier
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
