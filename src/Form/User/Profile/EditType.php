<?php
/**
 * Traders.Social
 *
 * @author Boris Wintein<boris.wintein@gmail.com>
 */

namespace App\Form\User\Profile;

use App\Form\AddressType;
use App\Form\User\InfoType;
use App\Http\Request\Handler\UserRegisterRequestHandler;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    parent::buildForm($builder, $options);
    
    $data = $options['data'];
    $builder
      ->setMethod(Request::METHOD_POST)
      ->add('userinfo', InfoType::class, ['data' => $data['info']])
      ->add('addresses', CollectionType::class, [
        'entry_type' => AddressType::class,
        'entry_options' => [
          'attr' => ['class' => 'address'],
        ],
        'allow_add' => true,
        'allow_delete' => true,
        'prototype' => true
      ])
    ;
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'csrf_protection' => true,
      'csrf_field_name' => '_token',
      'csrf_token_id'   => EditType::class,
    ));
  }
  
}