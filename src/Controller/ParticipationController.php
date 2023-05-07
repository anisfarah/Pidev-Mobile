<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Participation;
use App\Entity\Utilisateur;
use App\Form\ParticipationType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Mailer;
use Nucleos\DompdfBundle\Wrapper\DompdfWrapper;

#[Route('/participation')]
class ParticipationController extends AbstractController
{
    #[Route('/', name: 'app_participation_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $participations = $entityManager
            ->getRepository(Participation::class)
            ->findAll();

        return $this->render('participation/index.html.twig', [
            'participations' => $participations,
        ]);
    }

    #[Route('/new', name: 'app_participation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $participation = new Participation();
        $form = $this->createForm(ParticipationType::class, $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participation);
            $entityManager->flush();

            return $this->redirectToRoute('app_participation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participation/new.html.twig', [
            'participation' => $participation,
            'form' => $form,
        ]);
    }

    #[Route('/{idParticipation}', name: 'app_participation_show', methods: ['GET'])]
    public function show(Participation $participation): Response
    {
        return $this->render('participation/show.html.twig', [
            'participation' => $participation,
        ]);
    }

    #[Route('/{idParticipation}/edit', name: 'app_participation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Participation $participation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParticipationType::class, $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_participation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participation/edit.html.twig', [
            'participation' => $participation,
            'form' => $form,
        ]);
    }

    #[Route('/{idParticipation}', name: 'app_participation_delete', methods: ['POST'])]
    public function delete(Request $request, Participation $participation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participation->getIdParticipation(), $request->request->get('_token'))) {
            $entityManager->remove($participation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_participation_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/mail/{idEvent}', name: 'valider_part')]
    public function participer( Event $events , $idEvent,Mailer $mailer,  DompdfWrapper $dompdfWrapper,
    UtilisateurRepository $userRep
    ): Response
    {
        // Récupérer l'utilisateur statique une seule fois
        $entityManager = $this->getDoctrine()->getManager();

        // Récupérer l'utilisateur statique une seule fois
        $user = $userRep->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

        $event = $entityManager->getRepository(Event::class)->find($idEvent);
        $par = new Participation();
        $par->setIdEvent($events);
        $par->setIdUser($user);

        $entityManager->persist($par);
        $entityManager->flush();
          
         $html = $this->renderView('event/eventPdf.html.twig', [
             'event' => $events
         ]);
         $pdfOutput = $dompdfWrapper->getPdf($html);

          $mailer->sendEmail(
              $user->getEmail(),
              "Bonjour M/Mme " . $user->getPrenom() . " " . $user->getNom() . ".\n" . "Votre participation a notre evenement  
          a été faite avec succès! Nous vous remercions pour votre confiance. A bientôt !",
              "Etat Evenement",
               $pdfOutput,'Event.pdf'

          );
        return $this->render('participation/part_success.html.twig',[
            'event' => $events,

        ]);

    }
}