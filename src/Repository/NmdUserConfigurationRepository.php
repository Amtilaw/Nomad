<?php

namespace App\Repository;

use App\Entity\NmdUserConfiguration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NmdUserConfiguration|null find($id, $lockMode = null, $lockVersion = null)
 * @method NmdUserConfiguration|null findOneBy(array $criteria, array $orderBy = null)
 * @method NmdUserConfiguration[]    findAll()
 * @method NmdUserConfiguration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NmdUserConfigurationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NmdUserConfiguration::class);
    }

    public function UserConfigurationList($userId)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "    SELECT * 

                    FROM `nmd_user_configuration` A

                    LEFT JOIN `user` B
                    ON A.user_id = B.id

                    LEFT JOIN `nmd_partner` C
                    ON A.user_id = C.myaccount_id
 
                    WHERE B.id = ? 

               ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $userId);

        return $stmt->executeQuery()->fetchOne();

    }

    // /**
    //  * @return NmdUserConfiguration[] Returns an array of NmdUserConfiguration objects
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
    public function findOneBySomeField($value): ?NmdUserConfiguration
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
