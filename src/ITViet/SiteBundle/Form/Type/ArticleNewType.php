<?php

namespace ITViet\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use ITViet\SiteBundle\Form\Choices;

class ArticleNewType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $option) {
        $builder
          ->add('category')
          ->add('title', 'text')
          ->add('isActive', 'choice', array('choices' => Choices::$active, 'multiple' => false))
          ->add('content', 'textarea', array('required' => false));

    }
    public function getName() {
        return 'member';
    }
}
