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
                'label' => 'create_post.title',
                'translation_domain' => 'form'
            ])
            ->add('category', EntityType::class, [
                'label' => 'create_post.category',
                'translation_domain' => 'form',
                'class' => 'AppBundle\Entity\Category',
                'choice_label' => 'name'
            ])
            ->add('tag', EntityType::class, [
                'label' => 'create_post.tags',
                'translation_domain' => 'form',
                'class' => 'AppBundle\Entity\Tag',
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('content', TextareaType::class, [
                'label' => 'create_post.content',
                'translation_domain' => 'form',
                'attr' => [
                    'rows' => 5
                ]
            ])
            ->add('file', FileType::class, [
                'label' => 'Obraz'
            ])
            ->add('publishDate', DateTimeType::class, [
                'label' => 'create_post.publish_date',
                'translation_domain' => 'form'
            ])
            ->add('send', SubmitType::class, [
                'label' => 'submit',
                'translation_domain' => 'form'
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class
        ]);
    }


}