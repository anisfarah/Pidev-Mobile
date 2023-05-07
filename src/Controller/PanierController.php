<?php

namespace App\Controller;

use App\Entity\LignePanier;
use App\Entity\Livre;
use App\Entity\Panier;
use App\Entity\Utilisateur;
use App\Form\PanierType;
use App\Repository\LignePanierRepository;
use App\Repository\LivreRepository;
use App\Repository\PanierRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panier')]
class PanierController extends AbstractController
{
    #[Route('/', name: 'app_panier_index', methods: ['GET'])]
    public function index(
        PanierRepository $panierRepository,
        EntityManagerInterface $entityManager,
        LivreRepository $livRep,
        LignePanierRepository $ligRep,
        Request $request,
        UtilisateurRepository $userRep
    ): Response {
        $user = $userRep->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $panier = $panierRepository->findOneBy(['idUser' => $user]);

        $lignesPanier = [];
        $prixTotal = 0;

        if ($panier) {
            $lignesPanier = $ligRep->findBy(['idPanier' => $panier->getIdPanier()]);
            $prixTotal = $ligRep->calculerPrixTotal($panier->getIdPanier());
        }
        $session = $request->getSession();
        //$session->invalidate();
        $favoris = $session->get('favoris', []);

        $livresFavoris = [];
        foreach ($favoris as $livreId) {
            $livre = $entityManager->getRepository(Livre::class)->find($livreId);
            if ($livre) {
                $livresFavoris[] = $livre;
            }
        }

        return $this->render('panier/AjoutAuPanier.html.twig', [
            'panier' => $panier,
            'livres' => $livRep->findAll(),
            'lignesPanier' => $lignesPanier, 'prixTotal' => $prixTotal, 'livresFavoris' => $livresFavoris
        ]);
    }


    #[Route('/new/{livreId}', name: 'app_panier_new', methods: ['GET', 'POST'])]
    public function new(
        UtilisateurRepository $userRep,
        EntityManagerInterface $entityManager,
        PanierRepository $panierRepository,
        LignePanierRepository $lignePanierRepository,
        $livreId,
        LivreRepository $liv
    ): Response {
        $user = $userRep->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $panier = $panierRepository->findOneBy(['idUser' => $user]);
        $livre = $liv->find($livreId);
        
        if (!$panier) {
            $panier = new Panier();
            $panier->setMnttotal(0);
            $panier->setQte(0);
            $panier->setIdUser($user);
            $entityManager->persist($panier);
            $entityManager->flush();
        }

        $lignePanierExistante = $lignePanierRepository->findOneBy(['idLivre' => $livre, 'idPanier' => $panier]);

        if ($lignePanierExistante) {
            $quantite = $lignePanierExistante->getQte() + 1;
            $lignePanierExistante->setQte($quantite);
            $entityManager->persist($lignePanierExistante);
            $entityManager->flush();
            $message = "Quantité du livre dans le panier augmentée avec succès !";
        } else {
            $lignePanier = new LignePanier();
            $lignePanier->setIdLivre($livre);
            $lignePanier->setIdPanier($panier);
            $lignePanier->setQte(1);
            $entityManager->persist($lignePanier);
            $entityManager->flush();
        }


        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/{idPanier}', name: 'app_panier_delete', methods: ['POST'])]
    public function delete(Request $request, Panier $panier, PanierRepository $panierRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $panier->getIdPanier(), $request->request->get('_token'))) {
            $panierRepository->remove($panier, true);
        }

        return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/PanierNotcreated', name: 'app_404_panier')]
    public function errorPagePanier(): Response
    {

        return $this->render('home/errorPagePanier.html.twig');
    }

    #[Route('/pdf', name: 'pdf')]
    public function PDF(): Response
    {

        return $this->render('facture/FacturePdf.html.twig');
    }

    //AddTofavorite
    #[Route('/add/{idLivre}/favoris', name: 'ajouter_favoris')]
    public function ajouterFavoris(Request $request, Livre $livre)
    {
        $session = $request->getSession();
        $favoris = $session->get('favoris', []);
        if (!in_array($livre, $favoris)) {
            $favoris[] = $livre;
            $session->set('favoris', $favoris);
        }
        return $this->redirect($request->headers->get('referer'));
    }


    #[Route('/retirer/{idLivre}/favoris', name: 'retirer_favoris')]
    public function retirerFavoris(Request $request, Livre $livre)
    {
        $session = $request->getSession();
        $favoris = $session->get('favoris', []);

        $favoris = array_filter($favoris, function ($fav) use ($livre) {
            return $fav->getIdLivre() !== $livre->getIdLivre();
        });

        $session->set('favoris', $favoris);
        return $this->redirect($request->headers->get('referer'));
    }
}
