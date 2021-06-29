<?php

namespace App\Form;

use App\Entity\Chanel;
use App\Entity\Techno;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChanelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isValidate')
            ->add('budget')
            ->add('techno',null, [
                'class' => Techno::class, 
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
                'by_reference' => false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Chanel::class,
        ]);
    }
}
