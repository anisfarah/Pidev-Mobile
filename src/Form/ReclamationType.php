<?php

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\TypeRec;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu', TextareaType::class, [
                'attr' => array('cols' => '50', 'rows' => '5')
            ])->add('img', FileType::class, [
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
            ->add('typeRec', EntityType::class, array(
                'class' => TypeRec::class,
                'choice_label' => 'type',
                'expanded' => false,
                'multiple' => false,
            ))
            // ->add('id_user', EntityType::class,array(
            //     'class'=>Utilisateur::class,
            //     'choice_label'=>'nom',
            //     'expanded'=>false,
            //     'multiple'=>false,
            // ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
