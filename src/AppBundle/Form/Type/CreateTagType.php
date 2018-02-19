<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 01.02.18
 * Time: 09:34
 */

namespace AppBundle\Form\Type;


use AppBundle\Entity\Category;
use AppBundle\Entity\Map;
use AppBundle\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateTagType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'create_tag',
                'translation_domain' => 'form'
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Send',
                'translation_domain' => 'form'
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tag::class
        ]);
    }


}