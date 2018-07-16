<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 01.02.18
 * Time: 09:34
 */

namespace AppBundle\Form\Type;


use AppBundle\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateEventType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'event.title',
                'translation_domain' => 'form'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'event.description',
                'translation_domain' => 'form'
            ])
            ->add('eventDate', DateTimeType::class, [
                'label' => 'event.start_date',
                'translation_domain' => 'form',
                'data' => new \DateTime('now')
                ])
            ->add('endDateOfRegistration', DateTimeType::class, [
                'label' => 'event.registration_date',
                'translation_domain' => 'form',
                'data' => new \DateTime('now')
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'event.image',
                'translation_domain' => 'form',
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
            'data_class' => Event::class
        ]);
    }


}