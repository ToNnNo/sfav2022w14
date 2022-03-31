<?php

namespace App\Form;

use App\Entity\Post;
use App\Form\Custom\CustomFileType;
use App\Form\Transformer\StringToArrayModelTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    private $stringToArrayModelTransformer;

    public function __construct(StringToArrayModelTransformer $stringToArrayModelTransformer)
    {
        $this->stringToArrayModelTransformer = $stringToArrayModelTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'label.title',
                'attr' => [
                    'placeholder' => 'placeholder.title'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'label.content',
                'attr' => [
                    'placeholder' => 'placeholder.content'
                ]
            ])
            ->add('tags', TextType::class, [
                'label' => 'label.tags',
                'attr' => [
                    'placeholder' => 'placeholder.tags'
                ],
                'help' => 'help.tags'
            ])
            ->add('file', CustomFileType::class, [
                'label' => 'label.image',
                'attr' => [
                    'placeholder' => 'placeholder.image'
                ],
                'help' => 'help.image',
                'directory' => Post::IMAGE_DIRECTORY,
                // 'show_image' => false
            ])
        ;

        $builder->get('tags')->addModelTransformer($this->stringToArrayModelTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'required' => false
        ]);
    }
}
