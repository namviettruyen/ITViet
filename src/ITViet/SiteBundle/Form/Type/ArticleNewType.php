<?php

namespace ITViet\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use ITViet\SiteBundle\Form\Choices;

class ArticleNewType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $option) {
        $builder
          ->add('title', 'text');
    }
    public function getName() {
        return 'member';
    }
}
