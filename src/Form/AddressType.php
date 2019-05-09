<?php
/**
 * fishconnect
 * @author Boris Wintein<boris.wintein@gmail.com>
 */

namespace App\Form;


use App\Entity\User\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('address_reminder', TextType::class)
            ->add('address_1', TextType::class)
            ->add('address_2', TextType::class)
            ->add('city', TextType::class)
            ->add('province', TextType::class)
            ->add('postal_code', TextType::class)
            ->add('country', CountryType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Address::class,
        ));
    }

}