<?php

namespace App\Repository;

use App\Entity\LignePanier;
use App\Entity\Livre;
use App\Entity\Panier;
use App\Entity\Promo;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LignePanier>
 *
 * @method LignePanier|null find($id, $lockMode = null, $lockVersion = null)
 * @method LignePanier|null findOneBy(array $criteria, array $orderBy = null)
 * @method LignePanier[]    findAll()
 * @method LignePanier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LignePanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LignePanier::class);
    }

    public function calculerPrixTotal(int $id_panier): float
{
    $prixTotal = 0.0;
    $entityManager = $this->getEntityManager();

    $lignePanierRepository = $entityManager->getRepository(LignePanier::class);
    $livreRepository = $entityManager->getRepository(Livre::class);

    $lignesPanier = $lignePanierRepository->findBy(['idPanier' => $id_panier]);

    foreach ($lignesPanier as $lignePanier) {
        $idLivre = $lignePanier->getIdLivre()->getIdLivre();
        $qte = $lignePanier->getQte();

        $livre = $livreRepository->findOneBy(['idLivre' => $idLivre]);

        if ($livre) { 
            $prixLivre = $livre->getPrix();
            $promo = $livre->getCodePromo();

            if ($promo && $promo->getReduction() !== null) {
                $prixLivre = $prixLivre * (1 - $promo->getReduction() / 100);
            }

            // Calculer le prix total de la ligne de commande
            $prixTotal += $qte * $prixLivre;
        }
    }

    return $prixTotal;
}

    public function SuprimerAllLignePaniers(Panier $panier): void
    {
        $this->createQueryBuilder('lp')
            ->delete()
            ->where('lp.idPanier = :id_panier')
            ->setParameter('id_panier', $panier)
            ->getQuery()
            ->execute();
    }







    public function save(LignePanier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



    public function remove(LignePanier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    
    // public function removeLignePanierByLivre(Livre $livre)
    // {
    //     $select = 'lp.idLigne';
    //     $qb = $this->createQueryBuilder('lp')
    //         ->select($select)
    //         ->andWhere('lp.idLivre = :id_livre')
    //         ->setParameter('id_livre', $livre->getIdLivre())
    //         ->getQuery();

    //         $lignePanierIds = $qb->getResult();

    //         if ($lignePanierIds) {
    //             foreach ($lignePanierIds as $lignePanierId) {
    //                 $this->createQueryBuilder('lp')
    //                     ->delete()
    //                     ->andWhere('lp.idLigne = :id_ligne')
    //                     ->setParameter('id_ligne', $lignePanierId)
    //                     ->getQuery()
    //                     ->execute();
    //             }
    //         }
    //     }

    //    /**
    //     * @return LignePanier[] Returns an array of LignePanier objects
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

    //    public function findOneBySomeField($value): ?LignePanier
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
