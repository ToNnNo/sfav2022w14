<?php

namespace App\Form;

use App\Entity\Post;
use App\Form\Custom\CustomFileType;
use App\Form\Transformer\StringToArrayModelTransformer;
use App\Service\FileManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class PostType extends AbstractType
{
    private $stringToArrayModelTransformer;
    private $fileManager;

    public function __construct(StringToArrayModelTransformer $stringToArrayModelTransformer, FileManager $fileManager)
    {
        $this->stringToArrayModelTransformer = $stringToArrayModelTransformer;
        $this->fileManager = $fileManager;
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
                // 'show_image' => false,
                'constraints' => [
                    new Image()
                ]
            ])
        ;

        $builder->get('tags')->addModelTransformer($this->stringToArrayModelTransformer);

        $builder->addModelTransformer(new CallbackTransformer(
            // transform
            function(Post $post) {
                if(null !== $post->getImage()) {
                    $post->setFile(new File(Post::IMAGE_DIRECTORY.$post->getImage()));
                }

                return $post;
            },

            //reverseTransform
            function(Post $post) {
                /*if($post->getFile() instanceof UploadedFile) {
                    if( null !== $post->getImage() ) {
                        $this->fileManager->setExistingImage($post->getImage());
                    }

                    $name = $this->fileManager->setDirectory(Post::IMAGE_DIRECTORY)->uploadFile($post->getFile());
                    $post->setImage($name);
                }*/

                return $post;
            }
        ));

        /*$builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event){
            // @var Post $post
            $post = $event->getData();
            $form = $event->getForm();

            if($post->getFile() instanceof UploadedFile) {
                if( null !== $post->getImage() ) {
                    $this->fileManager->setExistingImage($post->getImage());
                }

                $name = $this->fileManager->setDirectory(Post::IMAGE_DIRECTORY)->uploadFile($post->getFile());
                $post->setImage($name);
            }
        });*/
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'required' => false
        ]);
    }
}
