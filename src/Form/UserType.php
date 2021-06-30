<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Techno;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'attr' =>
                [
                    'class' => 'uk-input uk-text-center',
                ]
            ])
            ->add('lastname', TextType::class,[
                'attr' => 
                [
                    'class' => 'uk-input uk-text-center'
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => 
                [
                    'class' => 'uk-input uk-text-center'
                ]
            ])
            ->add(
                'techno',
                EntityType::class,
                [
                    'class' => Techno::class,
                    'choice_label' => 'name',
                    'attr' => [
                        'class' => 'uk-select',
                    ]
                ])
            ->add('posterFile', FileType::class, [
                'required'      => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
