<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Theme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomEvent')
            ->add('lieuEvent')
            ->add('dateEvent', DateType::class,[
                'widget'=>'single_text',
                ])
            ->add('prixEvent')
            ->add('descEvent')
            ->add('image', FileType::class, [
                'mapped' => false])            
            ->add('idTheme' , EntityType ::class,[
                'class'=>Theme::class ,
                'choice_label'=>'nom_theme',
                'multiple'=>false ,
                'expanded'=>false,
                'placeholder' => 'Sélectionnez un thème',
                'required' => true, 
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez sélectionner un auteur.']),
                ],
            ])
            ;
            //->add('idUser')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
