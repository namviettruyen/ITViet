<?php

namespace ITViet\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use ITViet\SiteBundle\Form\Choices;

class MemberEditType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $option) {
        $_years = array();
        for ($y = date('Y')-13; $y >= date('Y')-70; $y--)
            $_years[$y] = $y;

        $builder
          ->add('fullName', 'text')
          ->add('gender', 'choice', array(
              'choices' => Choices::$gender,
              'required' => true,
              'multiple' => false,
              'empty_value' => '...',
              'error_bubbling' => true,
          ))
          ->add('address', 'text')
          ->add('birthDate', 'date', array(
              'input' => 'datetime',
              'required' => true,
              'widget' => 'choice',
              'format' => 'dd MM yyyy',
              'empty_value' => array('day' => 'Day', 'month' => 'Month', 'year' => 'Year'),
              'years' => $_years,
          ));
    }
    public function getName() {
        return 'member';
    }
}
