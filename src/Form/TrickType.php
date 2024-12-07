<?php

namespace App\Form;

use App\DTO\TrickDTO;
use App\Entity\Group;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('trickGroup', EntityType::class, [
                'class' => Group::class,
                'label' => 'Groupe',
                'choice_label' => 'name',
                'placeholder' => 'Groupe du Trick',
            ])
            ->add('illustrations', CollectionType::class, [
                'entry_type' => IllustrationType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrickDTO::class
        ]);
    }
}
