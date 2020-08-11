<?php

namespace App\Form;

use App\Entity\Album;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('release_date', DateTimeType::class,
               [
                   'widget'=> 'single_text',
               //    'format' => 'yyyy-MM-dd\'T\'HH:mm:ss.SSS',
                   'property_path' => 'releaseDate',
               ])
            ->add('track_count', NumberType::class,[
                'property_path' => 'trackCount'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
            'csrf_protection' => false
        ]);
    }
}
