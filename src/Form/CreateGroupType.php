<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Group;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CreateGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class, array(
            'constraints' => array(
                new Assert\NotBlank(array(
                    'message' => 'This field is required'
                )),
                new Assert\Length(array(
                    'min' => 2,
                    'max' => 255,
                    'minMessage' => 'Your group name must be more than 2 letters.',
                    'maxMessage' => 'Your group na must be less than 255 letters. '
                ))
                ),
        ))
            ->add('file', FileType::class, array(
                'required' => false
            ))
            // ->add('date')
            -> add('users', EntityType::class, array(
                'class' => User::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'username',
            ));
            // ->add('users_admin')

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
        ]);
    }
}
