<?php

namespace FileD\FileBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * User form type
 * @author epidoux <eric.pidoux@gmail.com>
 * @version 1.0
 *
 */
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FileD\FileBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'filed_filebundle_usertype';
    }
}
