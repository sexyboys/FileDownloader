<?php
namespace FileD\UserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends Admin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('username')
      ->add('email')
      ->add('plainPassword', 'text')
      ->add('enabled',null, array('required' => false))
      ->add('locked',null, array('required' => false))
      ->add('roles')
    ;
  }
 
  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('username')
      ->add('email')
      ->add('enabled')
      ->add('locked')
      ->add('roles')
    ;
  }
 
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('id')
      ->add('username')
      ->add('email')
      ->add('enabled')
      ->add('locked')
      ->add('roles')
    ;
  }
 
  public function validate(ErrorElement $errorElement, $object)
  {
    $errorElement
      ->with('username')
      ->assertMaxLength(array('limit' => 32))
      ->end()
    ;
  }
}