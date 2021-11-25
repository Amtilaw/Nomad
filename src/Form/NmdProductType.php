<?php

namespace App\Form;

use App\Entity\NmdProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\NmdCategorieProduct;

class NmdProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product', TextType::class, ['required' => false,])
            ->add('picture', TextType::class, ['required' => false,])
            ->add('price', NumberType::class, ['required' => false,])
            ->add('description', TextareaType::class, ['required' => false,])
//            ->add('createdAt', DateType::class, ['required' => false,])
            ->add('organizationPrice', NumberType::class, ['required' => false,])
            ->add('organizationNaming', TextType::class, ['required' => false,])
            ->add('organizationCategory', TextType::class, ['required' => false,])
 //           ->add('pictoMenu', ColorType::class, ['required' => false,])
            //->add('operatorId')
            ->add('isAdditionalProduct', CheckboxType::class)
  //          ->add('openingServiceCharges', TextType::class, ['required' => false,])
   //         ->add('closingServiceCharges', TextType::class, ['required' => false,])
            ->add('price2', NumberType::class, ['required' => false,])
    //        ->add('packageCode', TextType::class, ['required' => false,])
            ->add('remunerationCategorieName', TextType::class, ['required' => false,])
            ->add('remunerationProductName', TextType::class, [
                  'required' => false,
                  ])
            ->add('enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NmdProduct::class,
        ]);
    }
}
