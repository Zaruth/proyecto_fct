<?php

namespace FctBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FctType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('anyo', DateType::class, array("label" => "Fecha", "required" => "required", "attr" => array(
                        "class" => "form-name form-control"
            )))
                ->add('periodo', ChoiceType::class, array("label" => "Periodo", "required" => "required", "attr" => array(
                        "class" => "form-control"),
                    'choices' => array(
                        'Abril' => 'Abril',
                        'Septiembre' => 'Septiembre',
                        'Enero' => 'Enero',
            )))
                ->add('alumno', EntityType::class, array(
                    "label" => "Alumno",
                    "required" => "required",
                    'class' => 'FctBundle:Alumno',
                    'choice_label' => 'nombre', "attr" => array(
                        "class" => "form-name form-control")
                ))
                ->add('profesor', EntityType::class, array(
                    "label" => "Profesor",
                    "required" => "required",
                    'class' => 'FctBundle:Profesor',
                    'choice_label' => 'nombre', "attr" => array(
                        "class" => "form-name form-control")
                ))
                ->add('empresa', EntityType::class, array(
                    "label" => "Empresa",
                    "required" => "required",
                    'class' => 'FctBundle:Empresa',
                    'choice_label' => 'nombre', "attr" => array(
                        "class" => "form-name form-control")
                ))
                ->add('Crear', SubmitType::class, array("attr" => array(
                        "class" => "form-submit btn btn-success",
            )))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'FctBundle\Entity\Fct'
        ));
    }

}
