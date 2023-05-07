<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Facture;
use App\Entity\Livre;
use App\Entity\Utilisateur;
use App\Repository\FactureRepository;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DashboardAdminController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/dashboard', name: 'app_dashboard_admin')]
    public function index(
        EntityManagerInterface $entityManager,
    ): Response {
        $facture = $entityManager->getRepository(Facture::class)->findAll();
        $nbFacture = count($facture);
        $user = $entityManager->getRepository(Utilisateur::class)->findAll();
        $nbUser = count($user);
        $livre = $entityManager->getRepository(Livre::class)->findAll();
        $nbLivre = count($livre);
        $events = $entityManager->getRepository(Event::class)->findAll();
        $nbEvents = count($events);
        return $this->render('dashboard_admin/index.html.twig', [
            'nbFacture' => $nbFacture, 'nbUser' => $nbUser, 'nbLivre' => $nbLivre, 'nbEvents' => $nbEvents
        ]);
    }


    //facture
    #[Route('/factures_by_user', name: 'factures_by_user')]
    public function facturesByUser(FactureRepository $factureRepository)
    {
        $result = $factureRepository->findFacturesByUser();

        // retourner la réponse en JSON
        return $this->json(['result' => $result]);
    }

    #[Route('/factures_bestselling', name: 'factures_bestselling')]
    public function LivreLeplusAchetés(FactureRepository $factureRepository)
    {
        $result = $factureRepository->findBestSellingBooks();

        // retourner la réponse en JSON
        return $this->json(['result' => $result]);
    }


    //livre
    #[Route('/livres_by_categorie', name: 'livres_by_categorie')]
    public function livreByCategorie(LivreRepository $livreRepository)
    {
        $result = $livreRepository->findByCategorie();

        // retourner la réponse en JSON
        return $this->json(['result' => $result]);
    }

    #[Route('/livres_moins_cher', name: 'livres_moins_cher')]
    public function LivreLeMoinsCher(LivreRepository $livreRepository)
    {
        $result = $livreRepository->findBooksByPriceAscending();

        // retourner la réponse en JSON
        return $this->json(['result' => $result]);
    }

    #[Route('/static', name: 'app_static')]

    public function statistiquess()
    {
       
        $query = $this->entityManager->createQuery('SELECT COUNT(p.idParticipation) as total FROM App\Entity\Participation p');
        $result = $query->getSingleScalarResult();

        return $this->render('dashboard_admin/statPart.html.twig', [
            'statistiquess' => $result,
        ]);
    }

    #[Route('/statistique', name: 'app_statistique')]
    public function statistiques()
    {
        $query = $this->entityManager->createQuery('SELECT COUNT(e.idEvent) as total FROM App\Entity\Event e');
        $result = $query->getSingleScalarResult();

        return $this->render('dashboard_admin/statEvent.html.twig', [
            'statistiques' => $result,
        ]);
    }
}
