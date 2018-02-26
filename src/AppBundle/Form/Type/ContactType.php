<?php

namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->
            add('email', EmailType::class, [
                'label' => 'contact.email',
            'translation_domain' => 'form'
        ])
            ->add('subject', TextType::class, [
                'label' => 'contact.subject',
                'translation_domain' => 'form'

            ])
            ->add('message', TextareaType::class, [
                'label' => 'contact.message',
                'translation_domain' => 'form'
            ])
        ->add('submit', SubmitType::class, [
            'label' => 'submit',
            'translation_domain' => 'form'
        ]);
    }
}