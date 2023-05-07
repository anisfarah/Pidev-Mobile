<?php

namespace App\Form;

use App\Entity\Livre;
use App\Entity\Promo;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('libelle')
        ->add('description',TextareaType::class,[
            'attr'=>array('cols'=>'50','rows'=>'5')
        ])
        ->add('editeur')
        ->add('categorie')
        ->add('dateEdition', DateType::class,[
            'widget'=>'single_text',
            ])
        ->add('prix')
        ->add('langue')
        ->add('image',FileType::class,[
            'label' => 'image',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '2Mi',
                    'mimeTypesMessage' => 'Télécharger une image valide',
                ])
            ],
      
        ])
       /* ->add('codePromo' , EntityType ::class,[
            'class'=>Promo::class ,
            'choice_label'=>'code',
            'multiple'=>false ,
            'expanded'=>false,
            'placeholder' => 'Sélectionnez un codePromo',
        ])*/
        ->add('auteur' , EntityType ::class,[
            'class'=>Utilisateur::class ,
            'choice_label'=>'nom',
            'multiple'=>false ,
            'expanded'=>false,
            'placeholder' => 'Sélectionnez un auteur',
            'required' => true, 
            'constraints' => [
                new Assert\NotBlank(['message' => 'Veuillez sélectionner un auteur.']),
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}