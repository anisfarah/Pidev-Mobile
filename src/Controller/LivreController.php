<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use App\Repository\PromoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/livre')]
class LivreController extends AbstractController
{
    #[Route('/', name: 'app_livre')]
    public function index(LivreRepository $livreRepository): Response
    {
        $livres = $livreRepository->findAll();
        return $this->render('livre/index.html.twig', [
            'livres' => $livres,
        ]);
    }


    // #[Route('/front', name: 'app_livre_front')]
    // public function index1(LivreRepository $livreRepository): Response
    // {
    //     $livres = $livreRepository->findAll();
    //     return $this->render('livre/indexFront.html.twig', [
    //         'livres' => $livres,
    //     ]);
    // }

    


    #[Route('/add', name: 'add_livre')]
    public function Add(ManagerRegistry $doctrine , Request $request):Response
    {
        $livre=new Livre();
        $form= $this->createForm(LivreType::class,$livre);
        $form->handleRequest($request);      
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $image->getClientOriginalExtension();
                $newFilename = 'assets/back/images/' . $originalFilename . '.' . $extension;

    
                    $image->move(
                        $this->getParameter('dossier_images'),
                        $newFilename
                    );
                
    
                $livre->setImage($newFilename);
            }
            
            $entityManager = $doctrine->getManager();
            $entityManager->persist($livre);
            $entityManager->flush();
            $this->addFlash('success', 'Le livre a été ajouté avec succès.');
            return $this->redirectToRoute('app_livre');
        }
        return $this->renderForm('livre/add.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);  
       
    }


    #[Route('/edit/{idLivre}', name: 'edit_livre')]
    public function Edit(ManagerRegistry $doctrine , Request $request , LivreRepository $livreRepository , int $idLivre):Response
    {
        $livre=$livreRepository->find($idLivre);
        $form= $this->createForm(LivreType::class,$livre);
        $form->handleRequest($request);      
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
        if ($image) {
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $newFilename = 'assets/back/images/' . $originalFilename . '.' . $extension;
            $image->move(
                $this->getParameter('dossier_images'),
                $newFilename
            );
            $livre->setImage($newFilename);
        }
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            $this->addFlash('success1', 'Le livre a été modifié avec succès.');
            return $this->redirectToRoute('app_livre');
        }
        return $this->renderForm('livre/edit.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
         
       
    }

    #[Route('/detail/{idLivre}', name: 'detail_livre')]
    public function detail(int $idLivre ,LivreRepository $livreRepository )
    {
        $livre = $livreRepository->find($idLivre);

        if (!$livre) {
            throw $this->createNotFoundException('Livre non trouvée');
        }
        
    
    return $this->render('livre/detail.html.twig', [
        'livre' => $livre,
    ]);
  }


    #[Route('/delete/{idLivre}', name: 'delete_livre')]
    public function Delete(ManagerRegistry $doctrine ,$idLivre , LivreRepository $livreRepository):Response
    {
        $entityManager = $doctrine->getManager();
        $livre=$livreRepository->find($idLivre);
        $entityManager->remove($livre);
        $entityManager->flush();
        $this->addFlash('danger', 'Le livre a été supprimé avec succès.');
        return $this->redirectToRoute('app_livre');
    }

    #[Route('/search', name: 'search_livre')]
    public function search(Request $request, LivreRepository $livreRepository)
{
    $query = $request->query->get('query');
    $livres = $livreRepository->search($query);

    return $this->render('livre/index.html.twig', [
        'livres' => $livres,
    ]);
}



}