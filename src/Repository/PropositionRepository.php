<?php

namespace App\Repository;

use App\Entity\Proposition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Proposition|null find($id, $lockMode = null, $lockVersion = null)
 * @method Proposition|null findOneBy(array $criteria, array $orderBy = null)
 * @method Proposition[]    findAll()
 * @method Proposition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Proposition::class);
    }

    public function findIdQuestion($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT id_question_id
                FROM `proposition` p
                WHERE p.id = $id";

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function propositionParQuestion($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * FROM `proposition` p WHERE p.id_question_id = $id";

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function AllProposition()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT *
                FROM `proposition` ";

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    // /**
    //  * @return Proposition[] Returns an array of Proposition objects
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
    public function findOneBySomeField($value): ?Proposition
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
