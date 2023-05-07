<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reclamation>
 *
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    public function save(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function search(String $query)
    {
        $qb = $this->createQueryBuilder('reclamation');
        $qb->where($qb->expr()->like('reclamation.etat', ':rec'))
            ->setParameter('rec', '%' . $query . '%');
        return $qb->getQuery()->getResult();
    }
    public function findAllOrderByDateDESC()
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.dateRec', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findAllOrderByDateDESCF($userId)
    {
        return $this->createQueryBuilder('r')
            ->where('r.idUser = :idUser')
            ->setParameter('idUser', $userId)
            ->orderBy('r.dateRec', 'DESC')
            ->getQuery()
            ->getResult();
    }



    public function findAllOrderByDateASC()
    {
        return $this->createQueryBuilder('reclamation')
            ->orderBy('reclamation.dateRec', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllOrderByDateASCF($userId)
    {
        return $this->createQueryBuilder('r')
            ->where('r.idUser = :idUser')
            ->setParameter('idUser', $userId)
            ->orderBy('r.dateRec', 'ASC')
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Reclamation[] Returns an array of Reclamation objects
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

    public function findOne($value): ?Reclamation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.idRec = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findEtat($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.etat = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

    public function findByEtat()
    {
        return $this->createQueryBuilder('r')
            ->select('r.etat as etat, COUNT(r.idRec) as num_etats')
            ->groupBy('r.etat')
            ->getQuery()
            ->getResult();
    }
}
