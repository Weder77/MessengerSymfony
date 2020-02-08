<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username', TextType::class, array(
            'constraints' => array(
                new Assert\NotBlank(array(
                    'message' => 'This field is required'
                )),
                new Assert\Length(array(
                    'min' => 2,
                    'max' => 180,
                    'minMessage' => 'Your username must be more than 2 letters.',
                    'maxMessage' => 'Your username must be less than 180 letters. '
                ))
                ),
        ))

        ->add('password', PasswordType::class, array(
            'constraints' => array(
                new Assert\NotBlank(array(
                    'message' => 'This field is required'
                )),
                new Assert\Length(array(
                    'min' => 2,
                    'max' => 180,
                    'minMessage' => 'Your password must be more than 2 letters.',
                    'maxMessage' => 'Your password must be less than 180 letters. '
                ))
                ),
        ))


        ->add('email', EmailType::class)


        ->add('file', FileType::class, array(
            'required' => false,
        ))
        
    
        ->add('Register', SubmitType::class, [
            'attr' => ['class' => 'btn btn-custom'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => array(
                'novalidate' => 'novalidate' // = on ne veut pas de vérif html5
            )
        ]);
    }
}
