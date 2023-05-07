<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\LignePanierRepository;
use App\Repository\LivreRepository;
use App\Repository\PanierRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home/client', name: 'home')]
    public function home(PanierRepository $panierRepository, UtilisateurRepository $userRep, LignePanierRepository $ligRep): Response
    {
        // dd($this->getUser()->getUserIdentifier());
        $user = $userRep->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $panier = $panierRepository->findOneBy(['idUser' => $user]);
        if (!$panier) {
            return $this->render('home/index.html.twig');
        }
        $lignesPanier = $ligRep->findBy(['idPanier' => $panier->getIdPanier()]);
        $prixTotal = $ligRep->calculerPrixTotal($panier->getIdPanier());

        return $this->render('home/index.html.twig', [
            'panier' => $panier, 'lignesPanier' => $lignesPanier, 'prixTotal' => $prixTotal
        ]);
    }

    #[Route('/', name: 'app_home')]
    public function index(LivreRepository $livreRepository): Response
    {
        $livres = $livreRepository->findAll();
        $livre = $livreRepository->findOneBy(['prix'=>$livreRepository->findMinPrice()]);
        return $this->render('home/accueil.html.twig', [
            'livres' => $livres,
            'livre'=>$livre,
        ]);
    }
}
