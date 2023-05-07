<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\LigneFacture;
use App\Entity\LignePanier;
use App\Entity\Livre;
use App\Entity\Utilisateur;
use App\Repository\FactureRepository;
use App\Repository\LigneFactureRepository;
use App\Repository\LignePanierRepository;
use App\Repository\LivreRepository;
use App\Repository\PanierRepository;
use App\Repository\UtilisateurRepository;
use App\Service\Mailer;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nucleos\DompdfBundle\Wrapper\DompdfWrapper;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter;

class FactureController extends AbstractController
{
    #[Route('/facture/{id_panier}', name: 'app_facture')]
    public function factureUI(LignePanierRepository $ligRep, PanierRepository $panierRep, UtilisateurRepository $userRep, LivreRepository $livRep, int $id_panier): Response
    {
        $user = $userRep->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $panier = $panierRep->findOneBy(['idPanier' => $id_panier, 'idUser' => $user]);

        if (!$panier) {
            throw $this->createNotFoundException('The panier does not exist or does not belong to the current user');
        }

        // Get the lignesPanier for the panier
        $lignesPanier = $ligRep->findBy(['idPanier' => $id_panier]);
        $prixTotal = $ligRep->calculerPrixTotal($id_panier);

        // Get the Livres for each lignePanier
        $data = [];
        $sousTotal = 0;
        $qte_total = 0;
        $lignePanier = null; // Initialize the variable outside of the loop
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
                    $lignePanier = $lp; // Update the variable inside the loop
                }
                else {
                        $sousTotalLigne = $qte * ($livre->getPrix() );
                        $sousTotal += $sousTotalLigne;
                        $sousTotal = $qte * ($livre->getPrix() );
                        $data[] = $livre;
                        $data[count($data) - 1]->sousTotal = $sousTotalLigne;
                        $data[count($data) - 1]->qte = $qte;
                        $data[count($data) - 1]->idLigne = $idLigne;
                        $qte_total += $qte;
                        $lignePanier = $lp; // Update the variable inside the loop
                    
                }
        }
    }

        return $this->render('facture/index.html.twig', [
            'data' => $data,
            'prixTotal' => $prixTotal,
            'lignePanier' => $lignePanier,
            'lignesPanier' => $lignesPanier,
            'qte_total' => $qte_total,
            'panier' => $panier

        ]);
    }

    #[Route('/ajouter/{id_panier}', name: 'app_ajouter_facture')]
    public function ajouterFacture(
        Request $request,
        PanierRepository $panierRep,
        ManagerRegistry $entityManager,
        LignePanierRepository $ligRep,
        int $id_panier,
        DompdfWrapper $dompdfWrapper,
        Mailer $mailer,
        UtilisateurRepository $userRep
    ) {
        try {
            $em = $this->getDoctrine()->getManager();
            $user = $userRep->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
            $id_panier = $request->get('id_panier');
            $panier = $panierRep->findOneBy(['idPanier' => $id_panier, 'idUser' => $user]);
            // Calculer le montant total de la facture
            $montant_total = $ligRep->calculerPrixTotal($panier->getIdPanier());
            

            $mode_paiement = $request->get('payment_method');
            if ($mode_paiement === 'cheque') {
                $mode_paiement = 'Chèque';
            } else {
                $mode_paiement = 'Espèce';
            }

            // Ajouter la facture dans la table facture
            $facture = new Facture();
            $facture->setModePaiement($mode_paiement);
            $facture->setMntTotale($montant_total);
            $facture->setIdUser($panier->getIdUser());
            $facture->setDateFac(new \DateTime());
            $em->persist($facture);
            $em->flush();

            // Ajouter les lignes de facture dans la table ligne_facture
            $livreRepo = $entityManager->getRepository(Livre::class);
            $lpRepo = $entityManager->getRepository(LignePanier::class);
            $lignesPanier = $lpRepo->findBy(array('idPanier' => $panier));

            $currentDate = new DateTime();
            // $imageUrl = $cacheManager->getBrowserPath('/uploads/images/' . $lignesPanier->idLivre->getImage(), 'thumbnail');

            $html = $this->renderView('facture/FacturePdf.html.twig', [
                'factures' => $facture, 'total' => $montant_total,
                'lignesPanier' => $lignesPanier, 'panier' => $panier, 'user' => $user, 'currentDate' => $currentDate
            ]);
            $pdfOutput = $dompdfWrapper->getPdf($html);

            foreach ($lignesPanier as $lp) {
                $livre = $livreRepo->find($lp->getIdLivre()->getIdLivre());

                $ligneFacture = new LigneFacture();
                $ligneFacture->setIdFacture($facture);
                $ligneFacture->setIdLivre($livre);
                $ligneFacture->setQte($lp->getQte());
                $ligneFacture->setMnt($lp->getQte() * ($livre->getPrix() * (1 - ($livre->getCodePromo() ? $livre->getCodePromo()->getReduction() / 100 : 0))));
                $ligneFacture->setIdUser($user);
                $em->persist($ligneFacture);
                $em->flush();
                // Enlever la ligne du panier
                $em->remove($lp);
                $em->flush();
            }
            // Vider le panier de l'utilisateur
            $em->remove($panier);
            $em->flush();

            $mailer->sendEmail(
                $user->getEmail(),
                "Bonjour M/Mme " . $user->getPrenom() . " " . $user->getNom() . ".\n" . "Votre facture 
                a été faite avec succès! Nous vous remercions pour votre confiance. A bientôt !",
                "Etat facture",
                $pdfOutput,'facture.pdf'

            );

            $this->addFlash('success', 'Facture passée avec succès');
            return $this->redirect($this->generateUrl('app_panier_index'));
        } catch (\Exception $e) {
            echo "Une erreur est survenue : " . $e->getMessage();
            return new Response("Une erreur est survenue : " . $e->getMessage(), 500);
        }
    }



    #[Route('/MesFactures', name: 'mes_factures')]
    public function mesFactures(
        PanierRepository $panierRepository,
        UtilisateurRepository $userRep,
        LignePanierRepository $ligRep,
        Request $request,
    ): Response {
        $user = $userRep->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $panier = $panierRepository->findOneBy(['idUser' => $user]);
        $repository = $this->getDoctrine()->getManager()->getRepository(Facture::class);
        $query = $repository->createQueryBuilder('f')
            ->where('f.idUser = :idUser')
            ->setParameter('idUser', $user)
            ->getQuery();
        $factures = $query->getResult();

        $adapter = new ArrayAdapter($factures);
        $maxPerPage = 10;
        $pagerfanta = new Pagerfanta($adapter);
        $currentPage = $request->query->getInt('page', 1);
        $pagerfanta->setCurrentPage($currentPage);
        $pagerfanta->setMaxPerPage($maxPerPage);
        $factures = $pagerfanta->getCurrentPageResults();

        $totalItems = count($factures);
        $totalPages = (ceil($totalItems / $maxPerPage)) + 1;
        

        if ($panier) {
            $lignesPanier = $ligRep->findBy(['idPanier' => $panier->getIdPanier()]);
            $prixTotal = $ligRep->calculerPrixTotal($panier->getIdPanier());

            return $this->render('facture/facturesClt.html.twig', [
                'panier' => $panier, 'lignesPanier' => $lignesPanier, 'prixTotal' => $prixTotal,
                'factures' => $factures, 'pager' => $pagerfanta, 'totalPages' => $totalPages
            ]);
        } else {
            return $this->render('facture/facturesClt.html.twig', ['factures' => $factures, 'pager' => $pagerfanta, 'totalPages' => $totalPages]);
        }
    }

    #[Route('/detailsFactures/{idFacture}/{counter}', name: 'details_factures')]
    public function DétailsFactures(
        int $counter,
        int $idFacture,
        FactureRepository $factureRepository,
        PanierRepository $panierRepository,
        UtilisateurRepository $userRep,
        LignePanierRepository $ligRep,
    ): Response {
        $user = $userRep->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $panier = $panierRepository->findOneBy(['idUser' => $user]);
        $facture = $factureRepository->find($idFacture);
        $lignesFacture = $facture->getLignefactures();
        if ($panier) {
            $lignesPanier = $ligRep->findBy(['idPanier' => $panier->getIdPanier()]);
            $prixTotal = $ligRep->calculerPrixTotal($panier->getIdPanier());
            return $this->render('facture/DetailsFacClt.html.twig', [
                'facture' => $facture, 'lignesFacture' => $lignesFacture, 'counter' => $counter,
                'panier' => $panier, 'lignesPanier' => $lignesPanier, 'prixTotal' => $prixTotal
            ]);
        } else {
            return $this->render('facture/DetailsFacClt.html.twig', [
                'facture' => $facture, 'lignesFacture' => $lignesFacture, 'counter' => $counter,
            ]);
        }
    }


    #[Route('/factures', name: 'admin_factures')]
    public function FacturesAdmin(
        FactureRepository $factureRepository,
        Request $request,
        ManagerRegistry $doctrine = null,
    ): Response {
        $paiement = $request->query->get('paiement');
        $tri = $request->query->get('tri');
        $search = $request->query->get('search');

        if ($tri === 'default' || $paiement === "both") {
            return $this->redirect($this->generateUrl('admin_factures'));
        }

        $factures = $factureRepository->findAllSortedById();

        if ($search) {
            $factures = $factureRepository->searchByNomOrPrenom($search);
        }

        if ($paiement === 'espece') {
            $factures = $factureRepository->getFacturesEnEspece($doctrine->getManager());
        } else if ($paiement === 'cheque') {
            $factures = $factureRepository->getFacturesEnCheque($doctrine->getManager());
        } else {
            if ($tri === 'nomPreAsc') {
                $factures = $factureRepository->findAllSortedByNomPrenom('asc');
            } else if ($tri === 'nomPreDesc') {
                $factures = $factureRepository->findAllSortedByNomPrenom('desc');
            }
            if ($tri === 'TotalAsc') {
                $factures = $factureRepository->findAllSortedPrice('DESC');
            } else if ($tri === 'TotalDesc') {
                $factures = $factureRepository->findAllSortedPrice('asc');
            } else if ($tri === 'dateasc') {
                $factures = $factureRepository->findAllSortedDate('asc');
            } else if ($tri === 'datedesc') {
                $factures = $factureRepository->findAllSortedDate('DESC');
            }
        }

        $adapter = new QueryAdapter($factures);
        $maxPerPage = 10;
        $pagerfanta = new Pagerfanta($adapter);
        $currentPage = $request->query->getInt('page', 1);
        $pagerfanta->setCurrentPage($currentPage);
        $pagerfanta->setMaxPerPage($maxPerPage);
        $factures = $pagerfanta->getCurrentPageResults();



        $totalItems = count($factures);
        $totalPages = (ceil($totalItems / $maxPerPage)) + 1;





        return $this->render('facture/facturesAdmin.html.twig', [
            'factures' => $factures, 'search' => $search, 'pager' => $pagerfanta, 'totalPages' => $totalPages, 'tri' => $tri, 'paiement' => $paiement


        ]);
    }




    #[Route('/facture/{idFacture}/delete', name: 'facture_delete')]
    public function delete(
        $idFacture,
        Request $request,
        ManagerRegistry $doctrine,
        FactureRepository $rep,
        LigneFactureRepository $repLigne
    ): Response {

        $facture = $rep->find($idFacture);
        $ligneFactures = $repLigne->findBy(['idFacture' => $facture]);
        $em = $doctrine->getManager();
        foreach ($ligneFactures as $ligneFacture) {
            $em->remove($ligneFacture);
        }
        $em->remove($facture);

        $em->flush();

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }
}
