<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 01.02.18
 * Time: 09:34
 */

namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePermissionsType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('permissions', ChoiceType::class, [
                'label' => 'event.permissions',
                'translation_domain' => 'form',
                'choices' => [
                    'event.permissions_value.contestant' => 0,
                    'event.permissions_value.staff' => 1,
                    'event.permissions_value.admin' => 2
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'submit',
                'translation_domain' => 'form'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\EventSign'
        ]);
    }


}