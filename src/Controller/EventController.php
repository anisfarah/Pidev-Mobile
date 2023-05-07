<?php

namespace App\Controller;
use App\Repository\eventRepository;
use App\Repository\themeRepository;
use App\Entity\Event;
use App\Entity\Favoris;
use App\Entity\Theme;
use App\Entity\Utilisateur;
use App\Form\EventType;
use App\Repository\FavorisRepository;
use App\Repository\LignePanierRepository;
use App\Repository\PanierRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


#[Route('/event')]
class EventController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
{
    $this->session = $session;
}
    //Cette méthode affiche la liste de tous les événements enregistrés dans la base de données en appelant la méthode findAll() du repository Event.
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,FavorisRepository $favorisRepository): Response
    {
        $events = $entityManager
            ->getRepository(Event::class)
            ->findAll();

        return $this->render('event/index.html.twig', [
            //la variable  contenant la liste des événements
            'events' => $events,
        ]);
    }

    #[Route('/front', name: 'app_default', methods: ['GET'])]
    public function index1(EntityManagerInterface $entityManager,
    UtilisateurRepository $userRep,PanierRepository $panierRepository,
    LignePanierRepository $ligRep): Response
    {
        //contenu panier
        $user = $userRep->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $panier = $panierRepository->findOneBy(['idUser' => $user]);
        $events = $entityManager
            ->getRepository(Event::class)
            ->findAll();
       
        if (!$panier) {
            return $this->render('event/event.html.twig',[ 'e' => $events]);
        }
        $lignesPanier = $ligRep->findBy(['idPanier' => $panier->getIdPanier()]);
        $prixTotal = $ligRep->calculerPrixTotal($panier->getIdPanier());


        
        

        return $this->render('event/event.html.twig', [
            //la variable  contenant la liste des événements
            'e' => $events, 'panier' => $panier, 'lignesPanier' => $lignesPanier, 'prixTotal' => $prixTotal

        ]);
    }
    // #[Route('/def', name: 'app_default', methods: ['GET'])]
    // public function allEvents(EntityManagerInterface $entityManager): Response
    // {

    //     $events = $entityManager->getRepository(Event::class)->findAll();

    //     return $this->render('event/event.html.twig', [
    //         //la variable  contenant la liste des événements
    //         'e' => $events,
    //     ]);
    // }
//  créer un nouvel événement en cliquant sur le bouton "Nouvel événement"
    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = "assets/back/images/".$originalFilename. '.' .$image->guessExtension();
    
                    $image->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                
    
                $event->setImage($newFilename);
            }

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }
//afficher les details de levent fil back en gettant l id mte3o
    #[Route('/{idEvent}', name: 'app_event_show', methods: ['GET'])]
    public function show (Event $event):Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{idEvent}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();


            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = "assets/back/images/".$originalFilename.$image->guessExtension();

                
                    $image->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                

                $event->setImage($newFilename);
                //$evenement->setImage(new File($this->getParameter('images_directory').'/'.$newFilename));
            }
    
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{idEvent}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    { 
        //Le jeton CSRF (Cross-Site Request Forgery) est une mesure de sécurité  il est aussi genéré par twig :yaaml la verification de la supression keni  soumis par l'utilisateur actuel, et non pas par un tiers malveillant
        if ($this->isCsrfTokenValid('delete'.$event->getIdEvent(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }

  
   


    #[Route("/events/search", name:"event_search")]
    public function search(Request $request , EntityManagerInterface $entityManager )
    {
        $themes = $entityManager->getRepository(Theme::class)->findAll();

        $searchQuery = $request->query->get('q');

        $events = $this->getDoctrine()
            ->getRepository(Event::class)
            ->createQueryBuilder('o')
            ->where('o.nomEvent LIKE :query')
            ->orWhere('o.prixEvent LIKE :query')
            ->orWhere('o.descEvent LIKE :query')
            ->setParameter('query', '%'.$searchQuery.'%')
            ->getQuery()
            ->getResult();


            // $pagination = $paginator->paginate( $events, $request->query->getInt('page', 1 ), 6);

        return $this->render('event/search.html.twig', [
           // 'event' =>$pagination,
            // 'events' => $pagination, 
            'search_query' => $searchQuery,
            'events' => $events
        ]);

    }

    #[Route('/show/{idEvent}', name: 'app_details_show', methods: ['GET'])]
    public function showEvent(Event $event,UtilisateurRepository $userRep,PanierRepository $panierRepository,
    LignePanierRepository $ligRep): Response
    {
        $user = $userRep->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $panier = $panierRepository->findOneBy(['idUser' => $user]);

        $lignesPanier = $ligRep->findBy(['idPanier' => $panier->getIdPanier()]);
        $prixTotal = $ligRep->calculerPrixTotal($panier->getIdPanier());
        if (!$panier) {
            return $this->render('event/details.html.twig',[  'event' => $event]);
        }
        return $this->render('event/details.html.twig', [
            'event' => $event,'panier' => $panier, 'lignesPanier' => $lignesPanier, 'prixTotal' => $prixTotal
        ]);

    }

  

    #[Route('/favorite', name: 'myfavorites')]
    public function adzazd(UtilisateurRepository $userRep , FavorisRepository $favorisRepository)
    {
        die();

        $user = $this->getUser();
        dump($user); // Check if this returns a valid User object
        $favoris = $favorisRepository->findBy(['user' => $user]);

        
        return $this->render('event/favorites.html.twig', [
            'favoris' => $favoris,

        ]);
    }

    #[Route('/add-to-favorites/{idEvent}', name:'add_to_favorites')]
    public function addToFavorites($idEvent,UtilisateurRepository $userRep)
    {
        $entityManager = $this->getDoctrine()->getManager();


        $event = $entityManager->getRepository(Event::class)->find($idEvent);
        $user = $userRep->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        // Vérifier si l'utilisateur a déjà ajouté cet événement aux favoris
    $existingFavorite = $entityManager->getRepository(Favoris::class)->findOneBy(['idUser' => $user, 'idEvent' => $event]);

    if ($existingFavorite != null) {
        $this->addFlash('error', 'L\'événement est déjà dans vos favoris !');
        return $this->redirectToRoute('app_default', ['idEvent' => $idEvent]);
    }
        // Créer un nouvel objet Favourites
        $favourites = new Favoris();

        $favourites->setIdUser($user);
        $favourites->setIdEvent($event);

        // Enregistrer l'objet dans la base de données
        $entityManager->persist($favourites);
        $entityManager->flush();
        
        // Ajouter un message flash à la session de l'utilisateur
        $this->addFlash('success', 'L"événement a été ajouté aux favoris avec succès !');
        
        // Define notification variable
        $notification = '<div class="alert alert-success" role="alert">L"événement a été ajouté aux favoris avec succès !</div>';



        // Rediriger l'utilisateur vers la page de l'événement
       //return $this->redirectToRoute('app_detailsev_show', ['eventid' => $eventid]);
        return $this->redirectToRoute('app_default', ['idEvent' => $idEvent]);
    }

   

    #[Route('/favorite/delete/{idEvent}', name: 'delete_favorite')]
    public function deleteFavorite(Request $request, int $idEvent, EntityManagerInterface $entityManager,
    UtilisateurRepository $userRep): Response
    {
        $user = $userRep->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
       // $user = $this->getUser(); // Récupérer l'utilisateur courant


        // Trouver l'entrée correspondante dans la table des favoris
        $favorite = $entityManager->getRepository(Favoris::class)->findOneBy([
            'idEvent' => $idEvent,
            'idUser' => $user->getIdUser(),
        ]);

        // Vérifier si l'entrée a été trouvée
        if (!$favorite) {
            throw $this->createNotFoundException('Favori non trouvé');
        }

        // Supprimer l'entrée de la table des favoris
        $entityManager->remove($favorite);
        $entityManager->flush();
        dump($idEvent);

        $this->addFlash('success', 'Le favori a été supprimé avec succès.');

        return $this->redirectToRoute('myfavorites');
    }

}