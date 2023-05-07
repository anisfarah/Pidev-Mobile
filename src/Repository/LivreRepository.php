<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 *
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    public function save(Livre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Livre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   
public function search($query)
{
    $qb = $this->createQueryBuilder('livre');
    $qb->where($qb->expr()->like('livre.libelle', ':query'))
       ->setParameter('query', '%'.$query.'%');

    return $qb->getQuery()->getResult();
}


public function findBooksNotInPromo($id)
    {
        // Query to retrieve books that are not associated with the given promo
        return $this->createQueryBuilder('l')
            ->leftJoin('l.codePromo', 'p')
            ->andWhere('p.id != :id OR p.id IS NULL')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function findByCategorie()
    {
        return $this->createQueryBuilder('l')
        ->select('l.categorie as categorie, COUNT(l.idLivre) as num_categories')
        ->groupBy('l.categorie')
        ->getQuery()
        ->getResult();
    }


    public function findBooksByPriceAscending()
    {
        return $this->createQueryBuilder('l')
            ->select('l.libelle, l.prix ')
            ->orderBy('l.prix', 'ASC')
            ->getQuery()
            ->getResult();
    }
    

    public function findMinPrice()
    {
        return $this->createQueryBuilder('l')
            ->select('MIN(l.prix) as minPrice')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findMaxPrice()
    {
        return $this->createQueryBuilder('l')
            ->select('MAX(l.prix) as maxPrice')
            ->getQuery()
            ->getSingleScalarResult();
    }



//    /**
//     * @return Livre[] Returns an array of Livre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Livre
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}