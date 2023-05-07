<?php

namespace App\Controller;

use App\Entity\LignePanier;
use App\Entity\Utilisateur;
use App\Repository\LignePanierRepository;
use App\Repository\LivreRepository;
use App\Repository\PanierRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LignePanierController extends AbstractController
{
    #[Route('/lignepanier', name: 'app_ligne_panier')]
    public function index(LignePanierRepository $ligRep, UtilisateurRepository $userRep, PanierRepository $panierRep, LivreRepository $livRep): Response
    {
        $user = $userRep->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $panier = $panierRep->findOneBy(['idUser' => $user]);
        
        
        $lignesPanier = $ligRep->findBy(['idPanier' => $panier->getIdPanier()]);
        $prixTotal = $ligRep->calculerPrixTotal($panier->getIdPanier());

        $data = [];
        $sousTotal = 0;
        $qte_total = 0;
        $lignePanier = null;
        foreach ($lignesPanier as $lp) {
            $livreId = $lp->getIdLivre()->getIdLivre();
            $qte = $lp->getQte();
            $idLigne = $lp->getIdLigne();
            $livre = $livRep->findOneBy(['idLivre' => $livreId]);

            if ($livre) {
                if ($livre->getCodePromo() != null) {
                $sousTotalLigne = $qte * ($livre->getPrix() *  (1 - $livre->getCodePromo()->getReduction() / 100));
                $sousTotal += $sousTotalLigne;
                $sousTotal = $qte * ($livre->getPrix() *  (1 - $livre->getCodePromo()->getReduction() / 100));
                $data[] = $livre;
                $data[count($data) - 1]->sousTotal = $sousTotalLigne;
                $data[count($data) - 1]->qte = $qte;
                $data[count($data) - 1]->idLigne = $idLigne;
                $qte_total += $qte;
                $lignePanier = $lp; 
            }
            else {
                $sousTotalLigne = $qte * $livre->getPrix() ;
                $sousTotal += $sousTotalLigne;
                $sousTotal = $qte * $livre->getPrix();
                $data[] = $livre;
                $data[count($data) - 1]->sousTotal = $sousTotalLigne;
                $data[count($data) - 1]->qte = $qte;
                $data[count($data) - 1]->idLigne = $idLigne;
                $qte_total += $qte;
                $lignePanier = $lp; 
            }
        }
        }

        return $this->render('ligne_panier/index.html.twig', [
            'data' => $data,
            'prixTotal' => $prixTotal,
            'lignePanier' => $lignePanier,
            'lignesPanier' => $lignesPanier,
            'qte_total' => $qte_total,
            'panier' => $panier
        ]);
    }



    #[Route('/deleteLigne/{idLigne}', name: 'app_lignepanier_delete')]
    public function delete(Request $request, ManagerRegistry $doctrine, LignePanier $lignePanier, LignePanierRepository $lignePanierRepository): Response
    {
        // $lignePanierRepository->removeLignePanierByLivre($lignePanier->getIdLivre());
        $entityManager = $doctrine->getManager();
        $entityManager->remove($lignePanier);
        $entityManager->flush();


        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);    }

    #[Route('/deleteAllLignes/{id_panier}', name: 'app_AllLignepanier_delete')]
    public function deleteAllLigne(Request $request,PanierRepository $panierRep, ManagerRegistry $doctrine, 
    LignePanierRepository $lignePanierRepository, UtilisateurRepository $userRep,int $id_panier): Response
    {
        $user = $userRep->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $panier = $panierRep->findOneBy(['idPanier' => $id_panier, 'idUser' => $user]);
        
        $lignePanier = $lignePanierRepository->findBy(['idPanier' => $panier->getIdPanier()]);
        $lignePanierRepository->SuprimerAllLignePaniers($panier);

        $entityManager = $doctrine->getManager();
        foreach ($lignePanier as $lignePanierItem) {
            $entityManager->remove($lignePanierItem);
        }
        $entityManager->flush();


        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);    }

    // #[Route('/nav_ligne_panier', name: 'app_nav_ligne_panier')]
    // public function navLignePanier(UtilisateurRepository $userRep, PanierRepository $panierRep): Response
    // {
    //     $user = $userRep->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
    //     $panier = $panierRep->findOneBy(['idUser' => $user]);
    //     return $this->redirectToRoute('app_ligne_panier', [
    //         'id_panier' => $panier->getIdPanier(),
    //     ]);
    // }


    #[Route('/ligne-panier/{idLigne}/plus', name: 'increment_ligne_panier')]
    public function incrementLignePanier(LignePanier $lignePanier, EntityManagerInterface $entityManager): Response
    {
        $lignePanier->setQte($lignePanier->getQte() + 1);
        $entityManager->flush();
        return new JsonResponse([
            'success' => true,
            'newQty' => $lignePanier->getQte(),
        ]);
    }


    #[Route('/ligne-panier/{idLigne}/minus', name: 'decrement_ligne_panier')]
    public function decrementLignePanier(LignePanier $lignePanier, EntityManagerInterface $entityManager): Response
    {
        if ($lignePanier->getQte() > 1) {
            $lignePanier->setQte($lignePanier->getQte() - 1);
            $entityManager->flush();
        }
        return new JsonResponse([
            'success' => true,
            'newQty' => $lignePanier->getQte(),
        ]);
    }
}
