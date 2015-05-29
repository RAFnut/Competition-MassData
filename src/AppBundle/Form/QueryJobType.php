<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QueryJobType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lat', 'hidden', array('mapped' => false))
            ->add('lng', 'hidden', array('mapped' => false))
            ->add('radius', 'integer', array('mapped' => false))
            ->add('text', 'text', array('mapped' => false))
            ->add('start_date')
            ->add('end_date')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\QueryJob'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_query';
    }
}
