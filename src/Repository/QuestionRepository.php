<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function questionByVideoAndPallier($videoId, $idModule)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT DISTINCT q.libelle as questionLibelle,
                       q.id_type_id as tipe,
                       p.timecode,
                       p.titre_groupe_question,
                       q.id
                FROM `question` q, `pallier` p
                WHERE id_video_id = $videoId AND
                      q.id_pallier_id = p.id AND
                      q.id_module_id = $idModule
                ORDER BY `timecode`,`ordering` ASC ";

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    /**
     * @return Question[] Returns an array of NmdCategorieProduct objects
     */

    public function isAll()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =

            " SELECT *
            
          FROM `question`
        ";

        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function TimecodeQuestion($idTimecode)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =

            " SELECT timecode
            
          FROM `pallier`
          where id = $idTimecode
        ";

        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function findIdModule($idQuestion)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =

            " SELECT id_module_id
            
          FROM `question`
          where id = $idQuestion
        ";

        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function findByIdFormation($idQuestion)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =

            " SELECT module.id_formation_id
            
          FROM `question` question, `module` module
          where question.id = $idQuestion AND module.id = question.id_module_id
        ";

        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function getOrderingByPallier($idPallier)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =

            " SELECT ordering from question WHERE id_pallier_id = $idPallier ORDER BY `ordering` DESC
        ";

        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }


    // /**
    //  * @return Question[] Returns an array of Question objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Question
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
