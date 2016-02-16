<?php
// src/Dywee/UserBundle/Form/Type/RegistrationFormType.php

namespace Dywee\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('firstName', 'text')
            ->add('lastName', 'text')
            ->add('submit', 'submit');
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'dywee_user_registration';
    }
}
?>