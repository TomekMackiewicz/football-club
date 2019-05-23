<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\Post;
use App\Entity\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\DataTransformer\IdToCategoryTransformer;

class PostType extends AbstractType
{
    private $transformer;

    public function __construct(IdToCategoryTransformer $transformer)
    {
        $this->transformer = $transformer;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {     
        $builder
            ->add('title')
            ->add('body')
            ->add('slug')
            ->add('categories', ChoiceType::class, array(
                'choices' => $options['categories'],
                'multiple' => true,
                'expanded' => true
            ))
            ->add('images', EntityType::class, array(
                'class' => File::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ))
            ->add('save', SubmitType::class);
        
        $builder->get('categories')->addModelTransformer($this->transformer);
    }

    // TODO - tak?
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ])
        ->setDefault('categories', null)
        ->setRequired('categories')
        ->setAllowedTypes('categories', array('array'));
    }
}
