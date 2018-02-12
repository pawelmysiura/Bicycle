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
use AppBundle\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreatePostType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title'
            ])
            ->add('category', EntityType::class, [
                'label' => 'Category',
                'class' => 'AppBundle\Entity\Category',
                'choice_label' => 'name'
            ])
            ->add('tag', EntityType::class, [
                'label' => 'Tags',
                'class' => 'AppBundle\Entity\Tag',
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Content',
                'attr' => [
                    'rows' => 5
                ]
            ])
            ->add('publishDate', DateTimeType::class, [
                'label' => 'Publish date'
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Send'
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class
        ]);
    }


}