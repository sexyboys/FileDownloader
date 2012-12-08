<?php
namespace FileD\ParamBundle\Admin;

use Sonata\AdminBundle\Route\RouteCollection;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class ParameterAdmin extends Admin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('value')
    ;
  }
 
  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('key')
      ->add('value')
    ;
  }
 
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('key')
      ->add('value')
    ;
  }
 
  public function validate(ErrorElement $errorElement, $object)
  {
  	//no validation
  }
  
  protected function configureRoutes(RouteCollection $collection)
  {
  	$collection->remove('create');
  }
}