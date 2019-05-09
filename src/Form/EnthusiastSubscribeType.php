<?php
/**
 * fishconnect
 * @author Boris Wintein<boris.wintein@gmail.com>
 */

namespace App\Form;


use App\Entity\Enthusiast;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnthusiastSubscribeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        /** @var Enthusiast $enthusiast */
        $enthusiast = $options['data'];
        $builder
            ->add('name', TextType::class, ['attr' => ['placeholder' => 'Name']])
            ->add('email', TextType::class, ['attr' => ['placeholder' => 'Email']])
            ->add('uuid', HiddenType::class, ['data' => $enthusiast->getUuid()])
            ->add('signup', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Enthusiast::class,
        ));
    }
}