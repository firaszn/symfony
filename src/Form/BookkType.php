<?php

namespace App\Form;

use App\Entity\Bookk;
use App\Entity\Author; // Add this line to import the Author class
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ref')
            ->add('title')
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Science-Fiction' => 'Science-Fiction',
                    'Mystery' => 'Mystery',
                    'Autobiography' => 'Autobiography',
                ],
            ])
            ->add('publicationDate')
            ->add('published')
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'username',
                'multiple' => false,
                'expanded' => false,
                
            ])
                    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bookk::class,
        ]);
    }
}
