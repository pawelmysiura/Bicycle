<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 01.02.18
 * Time: 09:34
 */

namespace AppBundle\Form\Type;


use AppBundle\Entity\Map;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateMapType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'create_map.name',
                'translation_domain' => 'form'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'create_map.descritpion',
                'translation_domain' => 'form'
            ])
            ->add('image', CollectionType::class, [
                'label' => 'create_map.photos',
                'translation_domain' => 'form',
                'entry_type' => MapImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false
            ])
            ->add('start', HiddenType::class)
            ->add('end', HiddenType::class)
            ->add('waypoints', HiddenType::class)
            ->add('send', SubmitType::class, [
                'label' => 'submit',
                'translation_domain' => 'form'
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Map::class
        ]);
    }


}