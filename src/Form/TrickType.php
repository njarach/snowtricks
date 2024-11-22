<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\Trick;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'=> 'Nom du Trick',
                'attr'=>['placeholder'=>'Veuillez entrer le nom du Trick']
            ])
            ->add('description', TextareaType::class, [
                'label'=> 'Description du Trick',
                'attr'=>['placeholder'=>'Veuillez entrer la description du Trick']
            ])
            ->add('trick_group', EntityType::class, [
                'class' => Group::class,
                'label'=> 'Groupe',
                'choice_label' => 'name',
                'placeholder'=> 'Groupe du Trick'
            ])
            ->add('illustrations', FileType::class, [
                'label' => 'Illustrations',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'attr'     => [
                    'multiple' => 'multiple'
                ]
            ])
            ->add('videos', UrlType::class, [
                'label' => 'URLs Video',
                'mapped' => false,
                'required' => false,
                'attr'=>['placeholder'=>'Veuillez entrer un ou plusieurs liens embed séparés par des virgules.']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class
        ]);
    }
}
