<?php
/**
 * Traders.Social
 *
 * @author Boris Wintein<boris.wintein@gmail.com>
 */

namespace App\Form\Auction;


use App\Entity\Auction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    parent::buildForm($builder, $options);
    
    $builder
      ->add('title', TextType::class)
      ->add('description', TextareaType::class)
      ->add('media', FileType::class,
        [
          'mapped' => false,
          'required' => false,
        ]
      )
      ->add('publish', SubmitType::class);
    ;
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => Auction::class,
      'allow_file_upload' => true,
    ));
  }
}