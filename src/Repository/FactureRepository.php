<?php

namespace App\Repository;

use App\Entity\Facture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Facture>
 *
 * @method Facture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facture[]    findAll()
 * @method Facture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facture::class);
    }

    public function save(Facture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Facture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getFacturesEnEspece($entityManager): Query
    {
        $query = $entityManager->createQuery(
            'SELECT f
        FROM App\Entity\Facture f
        WHERE f.modePaiement = :modePaiement
        '
        )->setParameter('modePaiement', 'Espèce');

        return $query;
    }

    public function getFacturesEnCheque($entityManager): Query
    {
        $query = $entityManager->createQuery(
            'SELECT f
        FROM App\Entity\Facture f
        WHERE f.modePaiement = :modePaiement
        '
        )->setParameter('modePaiement', 'Chèque');

        return $query;
    }

    public function findAllSortedByNomPrenom($order = 'asc'): Query
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT f, u
            FROM App\Entity\Facture f
            JOIN f.idUser u
            ORDER BY u.nom ' . $order . ', u.prenom ' . $order
        );
        return $query;
    }


    public function findAllSortedById(): Query
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT f
        FROM App\Entity\Facture f
        ORDER BY f.idFacture ASC'
        );
        return $query;
    }

    public function findAllSortedPrice($order = 'ASC'): Query
{
    return $this->createQueryBuilder('f')
        ->orderBy('f.mntTotale', $order)
        ->getQuery();
}


    public function findAllSortedDate($order = 'ASC'): Query
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT f
        FROM App\Entity\Facture f
        ORDER BY f.dateFac ' . $order
        );
        return $query;
    }

    public function searchByNomOrPrenom(string $search)
    {
        $queryBuilder = $this->createQueryBuilder('f')
            ->leftJoin('f.idUser', 'u')
            ->where('u.nom LIKE :search')
            ->orWhere('u.prenom LIKE :search')
            ->setParameter('search', '%' . $search . '%');

        return $queryBuilder->getQuery();
    }

    public function findFacturesByUser()
    {
        $query = $this->getEntityManager()->createQuery('SELECT u.prenom, u.nom, COUNT(f) as num_factures 
            FROM App\Entity\Utilisateur u 
            LEFT JOIN u.factures f 
            WHERE u.idRole = :role
            GROUP BY u.idUser
        ');
        $query->setParameter('role', 2);
        $result = $query->getResult();

        return $result;
    }

    public function findBestSellingBooks()
    {
        $query = $this->getEntityManager()->createQuery(
            "SELECT l.libelle, COUNT(lf.idFacture) AS nb_factures
            FROM App\Entity\Livre l
            JOIN l.lignefactures lf
            JOIN lf.idFacture f
            GROUP BY l.libelle
            ORDER BY nb_factures DESC
            "
        );

        $result = $query->getResult();

        return $result;
    }







    //    /**
    //     * @return Facture[] Returns an array of Facture objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Facture
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
