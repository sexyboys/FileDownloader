<?php

namespace FileD\FileBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ShareFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('users', 'choice', array('choices' => $options['choices'],
						    	 		'multiple' => true,
						    	 		'expanded' => true,
						    	 		'empty_value' => $this->container->get('translator')->trans('file.share.list.empty'),
						    	 		'empty_data'  => -1
    	 		))
        		->add('id','hidden');
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FileD\FileBundle\Form\Data\Share'
        ));
    }

    public function getName()
    {
        return 'filed_filebundle_filesharetype';
    }
}
