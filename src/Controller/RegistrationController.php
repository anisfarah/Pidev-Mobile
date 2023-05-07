<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Repository\PanierRepository;
use App\Repository\RoleRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class RegistrationController extends AbstractController
{

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    #[Route('/registration', name: 'app_registration')]
    public function index(): Response
    {
        return $this->render('registration/index.html.twig', [
            'controller_name' => 'RegistrationController',
        ]);
    }
    #[Route('/sign', name: 'app_sign')]
    public function sign(UtilisateurRepository $userRep,PanierRepository $panierRepository ,        EntityManagerInterface $entityManager,
    RoleRepository $repo, Request $request): Response
    {
        $user = new Utilisateur();

        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));

            // Set their role
            $role = $repo->findOneBy(['role' => "Client"]);
            $user->setIdRole($role);
            $user->setStatus("activer");

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $user = $userRep->findOneBy(['idUser' => $user->getIdUser()]);
            $panier = $panierRepository->findOneBy(['idUser' => $user]);
            if (!$panier && $user->getIdRole()->getRole() == "Client" ) {
                $panier = new Panier();
                $panier->setMnttotal(0);
                $panier->setQte(0);
                $panier->setIdUser($user);
                $entityManager->persist($panier);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_login');
        }
    

        return $this->render('registration/registration.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }   


}
