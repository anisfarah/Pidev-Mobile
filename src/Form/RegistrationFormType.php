<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Doctrine\DBAL\Types\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Choice;
use Captcha\bundle\CaptchaBundle\Form\Type\Captchatype;
use Captcha\bundle\CaptchaBundle\Validator\Constraints\ValidCaptcha;
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;




class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
            ->add('email')
            ->add('Nom')
            ->add('Prenom')
            ->add('tel')
            
            ->add('datedenaissance', BirthdayType::class, [
                'label' => 'Date of Birth',
                'widget' => 'single_text',
                'required' => true,
                'attr' => [
                    'max' => (new \DateTime())->format('Y-m-d'), // Set the maximum date to today
                ],
            ])
            ->add('adresse')
            ->add('password', PasswordType::class);
            
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
            'attr'=>['novalidate'=>'novalidate']

        ]);
    }
}
