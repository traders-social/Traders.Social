<?php
/**
 * Created by PhpStorm.
 * User: Boris
 * Date: 28/10/2018
 * Time: 18:53
 */

namespace App\Form\User;

use App\Form\AddressType;
use App\Form\UserType;
use App\Http\Request\Handler\UserRegisterRequestHandler;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    parent::buildForm($builder, $options);
    
    $data = $options['data'];
    $builder
      ->setRequestHandler(new UserRegisterRequestHandler())
      ->setMethod(Request::METHOD_POST)
      ->add('user', UserType::class, ['data' => $data['user']])
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
      ->add('uuid', HiddenType::class, ['data' => $data['uuid']])
      ->add('register', SubmitType::class)
    ;
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'csrf_protection' => true,
      'csrf_field_name' => '_token',
      'csrf_token_id'   => RegisterType::class,
      'allow_file_upload' => true,
    ));
  }
}