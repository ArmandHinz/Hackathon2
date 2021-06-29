<?php

namespace App\Form;

use App\Entity\MessageSujet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageSujetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'message',
                TextareaType::class,
                [
                    'attr' => [
                        'class' => 'bg-gray-100 rounded border border-gray-400 leading-normal resize-none w-100 h-20  font-medium placeholder-gray-700 focus:outline-none focus:bg-white',
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MessageSujet::class,
        ]);
    }
}
