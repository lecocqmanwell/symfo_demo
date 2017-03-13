<?php
namespace AppBundle\Form;

use AppBundle\Entity\Adress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

    class AdressType extends AbstractType
    {
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $builder->add('street')
            ->add('town')
            ->add('postalcode');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    $resolver->setDefaults(array(
    'data_class' => Adress::class,
    ));
    }
}