<?php

namespace App\Repository;

use App\Entity\Raport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Raport>
 *
 * @method Raport|null find($id, $lockMode = null, $lockVersion = null)
 * @method Raport|null findOneBy(array $criteria, array $orderBy = null)
 * @method Raport[]    findAll()
 * @method Raport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Raport::class);
    }

    public function save(Raport $raport, bool $flush = false): void
    {
        $this->getEntityManager()->persist($raport);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Raport[] Returns an array of Raport objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Raport
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
