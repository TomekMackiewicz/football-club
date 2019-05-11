<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Category;
use App\Entity\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('slug')
            ->add('body')
            ->add('publishDate', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'
            ])
            ->add('modifyDate', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'
            ])
            ->add('categories', EntityType::class, array(
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ))
            ->add('images', EntityType::class, array(
                'class' => File::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ))
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
