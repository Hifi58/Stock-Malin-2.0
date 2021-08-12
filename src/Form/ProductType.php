<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', TextType::class, [
                'label'=> false,
                'required'=>true,
                'attr'=> array(
                    'placeholder'=>'Nom',
                    'class'=>'inputnew'
                )
            ])
            ->add('place', TextType::class, [
                'label'=> false,
                'required'=>true,
                'attr'=> array(
                    'placeholder'=>'Lieu d\'achat',
                    'class'=>'inputnew'
                )
            ])
            ->add('reference', IntegerType::class, [
                'label'=> false,
                'required'=>true,
                'attr'=> array(
                'placeholder'=>'Référence',
                'class'=>'inputnew'
                )
            ])
            ->add('price' , IntegerType::class, [
                'label'=> false,
                'required'=>true,
                'attr'=> array(
                'placeholder'=>'Prix',
                'class'=>'inputnew'
                )
            ])
            ->add('utilisation_tips', TextareaType::class, [
                'label'=> false,
                'required'=>true,
                'attr'=> array(
                    'placeholder'=>'Conseil d\'utilisation',
                    'class'=>'inputnew'
                )
            ])
            ->add('buying_date', DateType::class, [
                'label'=> 'Date d\'achat',
                'required'=>true,
                'attr'=> array(
                'class'=>'inputnew'
                )
            ])
            ->add('end_sav', DateType::class, [
                'label'=> 'Fin de garantie',
                'required'=>true,
                'attr'=> array(
                'class'=>'inputnew'
                )
            ])
            ->add('images', FileType::class, [
                'label' => 'Images',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'attr'=> array(
                'class'=>'inputnew'
                )
            ])
            ->add('files', FileType::class, [
                'label' => 'Manuel',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'attr'=> array(
                'class'=>'inputnew'
                )
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
    
}
