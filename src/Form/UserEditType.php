<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email', 'attr' => [
                    'class' => 'form-control form-control-user',
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom', 'attr' => [
                    'class' => 'form-control form-control-user',
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom', 'attr' => [
                    'class' => 'form-control form-control-user',
                ],
            ])
            ->add('tel', TelType::class, [
                'label' => 'Numéro de téléphone', 'attr' => [
                    'class' => 'form-control form-control-user',
                ],
            ], [
                'label' => 'Téléphone', 'attr' => [
                    'class' => 'form-control form-control-user',
                ],
            ])
            ->add('adresse', TextType::class, [
                'attr' => [
                    'class' => 'form-control form-control-user',
                ],
            ])
            ->add('datedenaissance', DateType::class, [
                'widget' => 'single_text',



                'attr' => [
                    'class' => 'form-control form-control-user',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
