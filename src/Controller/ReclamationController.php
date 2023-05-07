<?php

namespace App\Controller;
use App\Service\Mailer;
use App\Entity\Reclamation;
use App\Entity\Reponserec;
use App\Form\ReclamationType;
use App\Form\ReponserecType;
use App\Repository\ReclamationRepository;
use App\Repository\ReponserecRepository;
use App\Repository\TypeRecRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\FormError;

#[Route('/rec')]
class ReclamationController extends AbstractController
{
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(Request $request,ReclamationRepository $reclamationRepository): Response
    {
        $rec = $reclamationRepository->findAll();
        
        /*
        $rec = $pagi->paginate(
            $rec,
            $request->query->getInt('page',1),
            4
        );*/
        return $this->render('reclamation/indexAdmin.html.twig', [
            'reclamations' => $rec,
        ]);
    }
    #[Route('/front', name: 'app_reclamation_front', methods: ['GET'])]
    public function front(Request $request,ReclamationRepository $reclamationRepository,UtilisateurRepository $userRep): Response
    {
        // $rec = $reclamationRepository->findAll();
        $user = $userRep->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $rec =$reclamationRepository->findBy(['idUser'=> $user]);


        /*
        $rec = $pagi->paginate(
            $rec,
            $request->query->getInt('page',1),
            4
        );
*/
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $rec,
        ]);
    }

    #[Route('/search', name: 'app_reclamation_search')]
    public function search(Request $request, ReclamationRepository $reclamationRepository,UtilisateurRepository $userRep)
    {
    $rec = $request->query->get('rec');
    
    $reclamation = $reclamationRepository->search($rec);
    /*
    $reclamation = $pagi->paginate(
        $reclamation,
        $request->query->getInt('page',1),
        4
    );*/
    $user = $userRep->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

    if ($user->getIdRole()->getRole() == 'Admin')
    {

    return $this->render('reclamation/indexAdmin.html.twig', [
        'reclamations' => $reclamation
    ]);
    }
    else {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamation
        ]);


    }
}


#[Route('/ASC', name: 'tri_date_asc')]
public function OrderByDateASC(Request $request,ReclamationRepository $reclamationRepository,UtilisateurRepository $userRep)
    {
    /*
    $recs = $pagi->paginate(
        $recs,
        $request->query->getInt('page',1),
        4
    );*/
    $user = $userRep->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

    if ($user->getIdRole()->getRole() == 'Admin')
    {
        $recs = $reclamationRepository->findAllOrderByDateASC();
        return $this->render('reclamation/indexAdmin.html.twig', [
            'reclamations' => $recs,
        ]);

    }
    else {
        $recs = $reclamationRepository->findAllOrderByDateASCF($user);
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $recs,
        ]);
    }

   
    }

#[Route('/DESC', name: 'tri_date_desc')]
public function OrderDateDESC(Request $request,ReclamationRepository $reclamationRepository,UtilisateurRepository $userRep)
    {
    /*
    $recs = $pagi->paginate(
        $recs,
        $request->query->getInt('page',1),
        4
    );*/
    $user = $userRep->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

    if ($user->getIdRole()->getRole() == 'Admin')
    {
        $recs = $reclamationRepository->findAllOrderByDateDESC();

        return $this->render('reclamation/indexAdmin.html.twig', [
            'reclamations' => $recs,
        ]);

    }
    else {
        $recs = $reclamationRepository->findAllOrderByDateDESCF($user);

        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $recs,
        ]);
    }
   
    }

#[Route('/orderByDate', name: 'tri_date')]
public function OrderRecs(Request $request, UtilisateurRepository $userRep)
{
    $sort = $request->query->get('sort'); // Récupérer la valeur sélectionnée dans la liste déroulante
   
        

    if ($sort === 'asc') {
        // Redirection vers la route correspondant au tri ascendant
        return new RedirectResponse($this->generateUrl('tri_date_asc'));
    }
    elseif ($sort === 'desc') 
    {
        // Redirection vers la route correspondant au tri descendant
        return new RedirectResponse($this->generateUrl('tri_date_desc'));
    }

}
    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request,UtilisateurRepository $userRep ,ManagerRegistry $doctrine,Mailer $mailer): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        $etat="en cours";
        $utilisateur = $userRep->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);


        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier les mots dans le contenu
            $contenu = $form->get('contenu')->getData();
            if (!$this->verifMots($contenu, $form)) {
                return $this->renderForm('reclamation/new.html.twig', [
                    'reclamation' => $reclamation,
                    'form' => $form,
                ]);
            }
            $image = $form->get('img')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $image->getClientOriginalExtension();
                $newFilename = 'assets/front/images/' . $originalFilename . '.' . $extension;
                $image->move(
                    $this->getParameter('list_images'),
                    $newFilename
                );
                $reclamation->setImg($newFilename);
            }
            $reclamation->setDateRec(new \DateTime());
            $reclamation->setEtat($etat);
            $reclamation->setIdUser($utilisateur);
            $mailer->sendEmail(
                $utilisateur->getEmail(),
                "Bonjour M/Mme " . $utilisateur->getPrenom() . " " . $utilisateur->getNom() . ".\n" . "Votre reclamation 
                a été faite avec succès! Nous vous remercions pour votre confiance. A bientôt !" ,"Nouveau réclamation",null
            );
            $entityManager = $doctrine->getManager();
            $entityManager->persist($reclamation);
            $entityManager->flush();
            return $this->redirectToRoute('app_reclamation_front');
            }
        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }
    
    private function verifMots($contenu, $form) {
        $mots = array("amine", "akrimi", "pidev");
        foreach ($mots as $mot) {
            if (stripos($contenu, $mot) !== false) {
                $form->addError(new FormError("Le contenu ne peut pas contenir le mot interdit '" . $mot . "'."));
                return false;
            }
        }
        return true;
    }

    #[Route('/{idRec}/front', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation,TypeRecRepository $typeRep,UtilisateurRepository $utilisateurRepo): Response
    {
       
        $type = $typeRep->find($reclamation->getTypeRec());
        $utilisateur =$utilisateurRepo->find($reclamation->getIdUser());
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
            'type'=> $type,
            'util' =>$utilisateur,
        ]);
    }
    #[Route('/{idRec}', name: 'app_reclamation_show_admin', methods: ['GET'])]
    public function showAdmin(Reclamation $reclamation,TypeRecRepository $typeRep,UtilisateurRepository $utilisateurRepo): Response
    {
       
        $type = $typeRep->find($reclamation->getTypeRec());
        $utilisateur =$utilisateurRepo->find($reclamation->getIdUser());
        return $this->render('reclamation/showAdmin.html.twig', [
            'reclamation' => $reclamation,
            'type'=> $type,
            'util' =>$utilisateur,
        ]);
    }
    #[Route('/{idRec}/reponse', name: 'app_reclamation_reponse', methods: ['GET'])]
    public function reponse(Reclamation $reclamation,ReponserecRepository $repRepo,ReclamationRepository $recRepo, int $idRec): Response
    {
       
        $rep = $repRepo->findBy(['idReclamation'=> $idRec]);
        return $this->render('reclamation/reponse.html.twig', [
            'reclamation' => $reclamation,
            'reponse'=> $rep,
        ]);
    }


    #[Route('/{idRec}/repondre', name: 'app_reclamation_repondre', methods: ['GET', 'POST'])]
    public function repondre(Reclamation $rec,Request $request,ManagerRegistry $doctrine, ReponserecRepository $reponseRepo,ReclamationRepository $recRepo, int $idRec): Response
    { 

        $reponse = new Reponserec();
        $reclamation = new Reclamation();
        $form = $this->createForm(ReponserecType::class, $reponse);
        $form->handleRequest($request);
        $rec = $recRepo->find($idRec);

        if ($form->isSubmitted() && $form->isValid()) {
            $reponse->setIdReclamation($rec);
            $reponse->setDaterep(new \DateTime());
            $entityManager = $doctrine->getManager();
            $entityManager->persist($reponse);
            $entityManager->flush();
            $rec->setEtat("terminé");
            $entityManager1 = $doctrine->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('app_reclamation_index');
            }
        return $this->renderForm('reclamation/repondre.html.twig', [
            'reclamation' => $reponse,
            'form' => $form,
        ]);
    }    

    #[Route('/{idRec}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,ManagerRegistry $doctrine, ReclamationRepository $reclamationRepository, int $idRec): Response
    {
        $reclamation=$reclamationRepository->find($idRec);
        $form= $this->createForm(ReclamationType::class,$reclamation);
        $form->handleRequest($request);      
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('img')->getData();
        if ($image) {
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $newFilename = 'assets/front/images/' . $originalFilename . '.' . $extension;
            $image->move(
                $this->getParameter('list_images'),
                $newFilename
            );
            $reclamation->setImg($newFilename);
        }
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('app_reclamation_front');
        }
      
        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{idRec}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete($idRec): Response
    {
        $em=$this->getDoctrine()->getManager();
        $reclamation=$em->getRepository(Reclamation::class)->find($idRec);
        $em->remove($reclamation);
        $em->flush();
        return $this->redirectToRoute('app_reclamation_index');
    }


    }