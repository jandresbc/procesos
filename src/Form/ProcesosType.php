<?php

namespace App\Form;

use App\Entity\Procesos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


class ProcesosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion',TextareaType::class,[
                "label"=>"Descripción: *",
                "attr"=>["class"=>"form-control"]
            ])
            ->add('sede',ChoiceType::class,[
                "label"=>"Sede: ",
                "placeholder"=>"Seleccione Sede",
                "required"=>false,
                "choices"=>[
                    "Bogotá"=>"Bogotá DC.",
                    "Mexico"=>"Mexico",
                    "Perú"=>"Perú"
                ],
                "attr"=>["class"=>"form-control"]
            ])
            ->add('presupuesto',NumberType::class,[
                "label"=>"Presupuesto: *",
                "required"=>false,
                "attr"=>["class"=>"form-control"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Procesos::class,
        ]);
    }
}
