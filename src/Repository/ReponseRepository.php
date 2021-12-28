<?php

namespace App\Repository;

use App\Entity\Reponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reponse[]    findAll()
 * @method Reponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reponse::class);
    }

    public function reponseParModuleUtilisateurQuestion($id_module,$id_user)
    {
        $conn = $this->getEntityManager()->getConnection();
        

        $sql= "SELECT `module`.`id` as `moduleid`,question.libelle as `libelle`, `module`.`nom`,`reponse`.`answer`,`reponse`.`created_at`,`question`.`id` as `idquestion`,
        `module`.`nom`,`user`.`username`,`user`.`firstname` ,`user`.`id` ,type.nom as type, reponse.proposition_id
        FROM `question` 
        join `reponse`ON `question`.`id`=`reponse`.`question_id`
        join `module`ON `question`.`id_module_id`=`module`.`id`
        join`user`ON `reponse`.`user_id`=`user`.`id`
        join type on type.id = question.id_type_id
        where module.id = $id_module AND user.id=$id_user";


        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
        
    }
    public function listedesreponse()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT `module`.`id` as `moduleid`,`reponse`.`answer`,`reponse`.`created_at`,
        `proposition`.`libelle`,`module`.`nom`,`user`.`firstname`,`user`.`lastname`,`user`.`id` as `iduser`
         FROM `question`
        join `reponse` ON `question`.`id`=`reponse`.`question_id`
        join `module` ON `question`.`id_module_id`=`module`.`id`
        join `user` ON `reponse`.`user_id`=`user`.`id`
        left join `proposition` ON `proposition`.`id_question_id`=`question`.`id`
        GROUP by user.firstname, module.nom";

        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }
    // /**
    //  * @return Reponse[] Returns an array of Reponse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reponse
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
