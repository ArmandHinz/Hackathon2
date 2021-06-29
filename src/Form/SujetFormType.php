<?php

namespace App\Form;

use App\Entity\Sujet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Topic;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SujetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'uk-input',
                    ]
                ]
            )
            ->add(
                'shortDescription',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'uk-textarea',
                    ]
                ]
            )
            ->add(
                'longDescription',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'uk-textarea',
                    ]
                ]
            )
            ->add(
                'topic',
                EntityType::class,
                [
                    'class' => Topic::class,
                    'choice_label' => 'name',
                    'attr' => [
                        'class' => 'uk-select',
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sujet::class,
        ]);
    }
}