<?php

namespace FctBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CicloType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('abr',TextType::class, array("label"=>"Abreviatura","required"=>"required","attr" =>array(
                "class" => "form-name form-control",
                "maxlength"=>"10",
                "pattern"=>"([A-ZÑÁÉÍÓÚÜ1234567890º ']{2,10})",
                "title"=>"Abrebiatura de 2 a 10 carácteres(mayúsculas)"
                )))
            ->add('nombre',TextType::class, array("label"=>"Nombre","required"=>"required","attr" =>array(
                "class" => "form-name form-control",
                "maxlength"=>"50",
                "pattern"=>"([a-zA-ZñÑáéíóúÁÉÍÓÚüÜ1234567890º ']{10,50})",
                "title"=>"Nombre de 10 a 50 carácteres"
                )))
            ->add('grado', ChoiceType::class, array("label"=>"Grado","required"=>"required","attr" =>array(
                "class" => "form-control"),
                'choices'  => array(
                    'Superior' => 'Superior',
                    'Medio' => 'Medio',
                )))
            ->add('horas',TextType::class, array("label"=>"Horas","required"=>"required","attr" =>array(
                "class" => "form-name form-control",
                "maxlength"=>"11",
                "pattern"=>"\d{2,11}",
                "title"=>"Número de horas(hasta 11 dígitos)."
                )))
            ->add('Crear',SubmitType::class, array("attr" =>array(
                "class" => "form-submit btn btn-success",
                )))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FctBundle\Entity\Ciclo'
        ));
    }
}
