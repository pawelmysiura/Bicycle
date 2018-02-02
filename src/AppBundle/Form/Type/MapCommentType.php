<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 18.01.18
 * Time: 10:40
 */

namespace AppBundle\Form\Type;


use AppBundle\Entity\CommentMap;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MapCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment', TextareaType::class, [
                'label' => 'Comment',
                'attr' => [
                    'rows' => 3
                ]
                ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit'
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommentMap::class
        ]);
    }
}