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
                'label' => 'Email',
        ])
            ->add('subject', TextType::class, [
                'label' => 'Subject',
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
            ])
        ->add('submit', SubmitType::class, [
            'label' => 'Submit'
        ]);
    }
}