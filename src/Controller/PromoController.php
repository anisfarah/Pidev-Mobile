<?php

namespace App\Controller;

use App\Entity\Promo;
use App\Entity\Utilisateur;
use App\Form\PromoType;
use App\Repository\LivreRepository;
use App\Repository\PromoRepository;
use App\Service\Mailer;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Nucleos\DompdfBundle\Wrapper\DompdfWrapper;
use Symfony\Component\Mailer\MailerInterface;

#[Route('/promo')]
class PromoController extends AbstractController
{
    #[Route('/', name: 'app_promo')]
    public function index(PromoRepository $promoRepository , Request $request ): Response
    {
        $promos = $promoRepository->findAll();
        return $this->render('promo/index.html.twig', [
            'promos' => $promos,
        ]);
    }


    #[Route('/ASC', name: 'tri_promo_asc')]
    public function OrderByReductionASC(PromoRepository $promoRepository)
    {
        $promos = $promoRepository->findAllOrderByReductionASC();

        return $this->render('promo/index.html.twig', [
            'promos' => $promos,
        ]);
    }

    #[Route('/DESC', name: 'tri_promo_desc')]
    public function OrderByReductionDESC(PromoRepository $promoRepository)
    {
        $promos = $promoRepository->findAllOrderByReductionDESC();

        return $this->render('promo/index.html.twig', [
            'promos' => $promos,
        ]);
    }


    #[Route('/addLivre_promo/{id}', methods: ["GET"], name: 'show_livre_promo')]
    public function showAddLivreToPromoForm(Request $request, LivreRepository $livreRepository, EntityManagerInterface $entityManager, $id): Response
    {
        // Retrieve the Promo entity using the EntityManager
        $promo = $entityManager->getRepository(Promo::class)->find($id);
    
        // Check if the promo entity exists
        if (!$promo) {
            throw $this->createNotFoundException('Promo not found');
        }
    
        // Récupérer la liste des livres disponibles depuis votre source de données
        $livresDisponibles = $livreRepository->findAll();
    
        // Afficher la vue Twig dédiée pour sélectionner un livre
        return $this->render('promo/add_livre.html.twig', [
            'promo' => $promo,
            'livresDisponibles' => $livresDisponibles,
        ]);
    }


    


#[Route('/addLivreTOpromo/{idLivre}/{id}', methods: ["POST"], name: 'add_livre_to_promo')]
public function addLivreToPromo(Request $request, LivreRepository $livreRepository, PromoRepository $promoRepository, EntityManagerInterface $entityManager, $idLivre, $id): Response
{
    // Récupérer le livre et la promo en utilisant leur ID
    $livre = $livreRepository->find($idLivre);
    $promo = $promoRepository->find($id);

    // Vérifier si le livre et la promo existent
    if (!$livre || !$promo) {
        throw $this->createNotFoundException('Livre or Promo not found');
    }

    // Ajouter le livre à la promo
    $promo->addLivre($livre);
    $entityManager->persist($promo);
    $entityManager->flush();
    $this->addFlash('success', 'Le livre a été ajoutée avec succès.');

    // Rediriger vers la page de la promo
    return $this->redirectToRoute('detail_promo', ['id' => $id]);
}


#[Route('/removeLivreFromPromo/{idLivre}/{id}', methods: ["POST"], name: 'remove_livre_from_promo')]
public function removeLivreFromPromo(Request $request, LivreRepository $livreRepository, PromoRepository $promoRepository, EntityManagerInterface $entityManager, $idLivre, $id): Response
{
    // Récupérer le livre et la promo en utilisant leurs IDs
    $livre = $livreRepository->find($idLivre);
    $promo = $promoRepository->find($id);

    // Vérifier si le livre et la promo existent
    if (!$livre || !$promo) {
        throw $this->createNotFoundException('Livre or Promo not found');
    }

    // Supprimer le livre de la promo
    $promo->removeLivre($livre);
    $entityManager->flush();
    $this->addFlash('danger', 'Le livre a été supprimée avec succès.');

    // Rediriger vers la page appropriée après la suppression
    return $this->redirectToRoute('detail_promo', ['id' => $id]);
}



    #[Route('/orderByReduction', name: 'tri_promo')]
    public function OrderPromos(Request $request)
    {
        $sort = $request->query->get('sort'); // Récupérer la valeur sélectionnée dans la liste déroulante
        if ($sort === 'asc') {
            // Redirection vers la route correspondant au tri ascendant
            return new RedirectResponse($this->generateUrl('tri_promo_asc'));
        }
        if ($sort === 'desc') 
        {
            // Redirection vers la route correspondant au tri descendant
            return new RedirectResponse($this->generateUrl('tri_promo_desc'));
        }
    }


    #[Route('/add', name: 'add_promo')]
    public function Add(ManagerRegistry $doctrine , Request $request):Response
    {
        $promo=new Promo();
        $form= $this->createForm(PromoType::class,$promo);
        $form->handleRequest($request);      
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($promo);
            $entityManager->flush();
            $this->addFlash('success', 'La promotion a été ajoutée avec succès.');
            return $this->redirectToRoute('app_promo');
        }
        
        return $this->renderForm('promo/add.html.twig', [
            'promo' => $promo,
            'form' => $form,
        ]);  
       
    }


    #[Route('/edit/{id}', name: 'edit_promo')]
    public function Edit(ManagerRegistry $doctrine , Request $request , PromoRepository $promoRepository , int $id):Response
    {
        $promo=$promoRepository->find($id);
        $form= $this->createForm(PromoType::class,$promo);
        $form->handleRequest($request);      
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            $this->addFlash('success1', 'La promotion a été modifiée avec succès.');
            return $this->redirectToRoute('app_promo');
        }
        return $this->renderForm('promo/edit.html.twig', [
            'promo' => $promo,
            'form' => $form,
        ]);  
       
    }


    #[Route('/delete/{id}', name: 'delete_promo')]
    public function Delete(ManagerRegistry $doctrine ,$id, PromoRepository $promoRepository):Response
    {
        $promo=$promoRepository->find($id);
        $entityManager = $doctrine->getManager();
        $entityManager->remove($promo);
        $entityManager->flush();
        $this->addFlash('danger', 'La promotion a été supprimée avec succès.');
        return $this->redirectToRoute('app_promo');
    }

    #[Route('/search', name: 'search_promo')]
    public function search(Request $request, PromoRepository $promoRepository)
    {
        $query = $request->query->get('query');
        $promos = $promoRepository->search($query);
        return $this->render('promo/index.html.twig', [
        'promos' => $promos,
        ]);
    }


    #[Route('/detail/{id}', name: 'detail_promo')]
    public function detail(int $id ,PromoRepository $promoRepository )
    {
        $promo = $promoRepository->find($id);

        if (!$promo) {
            throw $this->createNotFoundException('Promotion non trouvée');
        }
        
        $livres = $promo->getLivres();
        $date = new DateTime();
        $now = $date->format('Y-m-d');
        
    
    return $this->render('promo/detail.html.twig', [
        'promo' => $promo,
        'livres' => $livres,
        'now'=>$now
    ]);
  }


    #[Route('/cataloguePromo', name: 'catalogue_promo')]
    public function cataloguePromo(PromoRepository $promoRepository)
    {
        $promos = $promoRepository->findAll();
        
        // Récupérer les livres associés à chaque promotion
        $livres = [];
        foreach ($promos as $promo) {
        $livres[] = $promo->getLivres();
        
    }
    return $this->render('promo/catalogue.html.twig', [
        'promos' => $promos,
        'livres' => $livres,
    ]);
  }


  
 #[Route('/cataloguePDF', name: 'catalogue_promo_pdf')]
  public function generateCataloguePdf( PromoRepository $promoRepository , DompdfWrapper $dompdfWrapper,Mailer $mailer , MailerInterface $mailerInterface )
{
    
    $promos = $promoRepository->findAll();
    

    $template = $this->renderView('promo/pdf.html.twig',[
        'promos' => $promos,
    ]);
    // Créer une instance de Dompdf
    $dompdf = new Dompdf();

    // Ajouter le contenu au Dompdf
    $dompdf->loadHtml($template);

    // Rendre le contenu en PDF
    $dompdf->render();

    // Récupérer le contenu généré en PDF
    $pdfContent = $dompdf->output();
    

    // Créer une réponse HTTP avec le contenu PDF
    $response = new Response($pdfContent);

    // Définir les en-têtes de réponse pour indiquer qu'il s'agit d'un fichier PDF
   
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment;filename="catalogue.pdf"');

    $pdfOutput = $dompdfWrapper->getPdf($template);

     $mailer->sendEmail('mechmechwissal@gmail.com', 
     'Nouveau catalogue', 'Un nouveau catalogue a été génére.',$pdfOutput,'Catalogue.pdf');

    return $response;
}


}