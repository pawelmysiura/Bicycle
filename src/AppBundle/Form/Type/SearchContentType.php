<?php

namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->
            add('search', SearchType::class, [
                'attr' => [
                    'placeholder' => 'search.submit'
                ],
            'translation_domain' => 'form'
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'search.submit',
            'translation_domain' => 'form'
        ]);
    }
}