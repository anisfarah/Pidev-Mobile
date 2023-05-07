<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UserEditType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UtilisateurController extends AbstractController
{
  
    #[Route('/users', name: 'app_user_index', methods: ['GET','POST'])]
    // #[IsGranted('ROLE_ADMIN')]
    public function index(EntityManagerInterface $entityManager,UtilisateurRepository $userRepository, Request $request): Response
    {
      
            
        $users = $entityManager
            ->getRepository(Utilisateur::class)
            ->findAll();

        $back = null;
        if($request->isMethod("POST")){
            if ( $request->request->get('optionsRadios')){
                $SortKey = $request->request->get('optionsRadios');
                switch ($SortKey){


                    case 'Nom':
                        $users = $userRepository->SortByNom();
                        break;

                    case 'Telephone':
                        $users = $userRepository->SortByTelephone();
                        break;


                }
            }
            else
            {
                $type = $request->request->get('optionsearch');
                $value = $request->request->get('Search');
                switch ($type){


                    case 'Nom':
                        $users = $userRepository->findByNom($value);
                        break;


                    case 'Telephone':
                        $users = $userRepository->findByTelephone($value);
                        break;

                    

                }
            }

            if ( $users){
                $back = "success";
            }else{
                $back = "failure";
            }
        }

        return $this->render('utilisateur/index.html.twig', [
            'users' => $users, 'back'=>$back
        ]);
    }

    #[Route('/client/profile/modifier', name: 'clientProfile',methods: ['GET', 'POST'])]

    public function userProfile(ManagerRegistry $doctrine, Request $request, UtilisateurRepository $repository, SluggerInterface $slugger): response
    {
        $user= $this->getUser();
        $form=$this->createForm(UserEditType::class,$user);

        $form->handleRequest($request);
        $errors = $form->getErrors();


        if ($form->isSubmitted() && $form->isValid()) {




            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('clientProfile');
        }

        return $this->render('utilisateur/editClient.html.twig', [
            'form' => $form->createView(),
            'errors' => $errors

        ]);
    }

    #[Route('/user/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UtilisateurRepository $userRepository): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/user/{idUser}', name: 'app_user_show', methods: ['GET'])]
    public function show(Utilisateur $user): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/{idUser}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $user, UtilisateurRepository $userRepository): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);
            
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/userdel/{idUser}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $user, UtilisateurRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getIdUser(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/user/activer/{idUser}', name: 'app_activer')]
    public function activer(ManagerRegistry $doctrine, $idUser)
    {
        $user = $doctrine->getRepository(Utilisateur::class)->find($idUser);
        $user->setStatus("activer");
        $entityManager = $doctrine->getManager();

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_index');
        
    }
    #[Route('/user/desactiver/{idUser}', name: 'app_desactiver')]
    public function desactiver(ManagerRegistry $doctrine, $idUser)
    {
        $user = $doctrine->getRepository(Utilisateur::class)->find($idUser);
        $user->setStatus("desactiver");
        $entityManager = $doctrine->getManager();

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_index');
    }

}
