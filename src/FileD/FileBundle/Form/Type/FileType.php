<?php

namespace FileD\FileBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * File Type
 * @author epidoux <eric.pidoux@gmail.com>
 * @version 1.0
 *
 */
class FileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FileD\FileBundle\Entity\File'
        ));
    }

    public function getName()
    {
        return 'filed_filebundle_filetype';
    }
}
