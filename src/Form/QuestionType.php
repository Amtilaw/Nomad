<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('CreatedAt')
            ->add('modifyAt')
            ->add('ordering')
            ->add('id_pallier')
            ->add('id_lvl')
            ->add('id_module')
            ->add('id_video')
            ->add('id_pdf')
            ->add('id_type')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
