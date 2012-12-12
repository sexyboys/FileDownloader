<?php

namespace FileD\FileBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Sharing file form type
 * @author epidoux <eric.pidoux@gmail.com>
 * @version 1.0
 *
 */
class ShareFileType extends AbstractType
{
	private $choices;
	
	/**
	 * Constructor
	 * @param array of choices $choices
	 */
	public function __construct($choices){
		$this->choices = $choices;	
	}
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('users', 'choice', array('choices' => $this->choices,
        								'label' => $options['label'],
						    	 		'multiple' => true,
						    	 		'expanded' => true
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
