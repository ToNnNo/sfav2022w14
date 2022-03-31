<?php

namespace App\Form\Custom;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomFileType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['show_image'] = $options['show_image'];
        $view->vars['directory'] = $options['directory'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('directory'); // option obligatoire
        $resolver->setAllowedTypes('directory', 'string');

        $resolver->setDefined('show_image'); // option existante
        $resolver->setAllowedTypes('show_image', 'bool');
        $resolver->setDefault('show_image', true);

        $resolver->setDefaults([]);
    }

    public function getParent()
    {
        return FileType::class;
    }
}
