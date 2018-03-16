<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 01.02.18
 * Time: 09:34
 */

namespace AppBundle\Form\Type;


use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvatarType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', FileType::class, [
                'label' => 'user.avatar',
                'translation_domain' => 'form'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'submit',
                'translation_domain' => 'form'
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }


}