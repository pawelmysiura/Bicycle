<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 01.02.18
 * Time: 09:34
 */

namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ChangeNumberType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startNumber', IntegerType::class, [
                'label' => 'event.start_number',
                'translation_domain' => 'form'
            ])
            ->add('changeNumber', CheckboxType::class, [
                'label' => 'event.change_number',
                'translation_domain' => 'form',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'submit',
                'translation_domain' => 'form'
            ]);
    }


}