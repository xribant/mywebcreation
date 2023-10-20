<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer votre nom')
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre nom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer votre e-mail'),
                    new Email(message: 'Veuillez entrer une adresse e-mail valide')
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre e-mail'
                ]
            ])
            ->add('subject', TextType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer l\'objet de votre message')
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Objet du message'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank(message: 'Merci de me décrire votre besoin')
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Message',
                    'rows' => 10
                ]
            ])
            ->add('recaptcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'homepage',
                'locale' => 'fr',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
