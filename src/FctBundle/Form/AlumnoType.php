<?php

namespace FctBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class AlumnoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nif',TextType::class, array("label"=>"Nif","required"=>"required","attr" =>array(
                "class" => "form-name form-control",
                "maxlength"=>"9",
                "pattern"=>"(([X-Z]{1})([-]?)(\d{7})([-]?)([A-Z]{1}))|((\d{8})([-]?)([A-Z]{1}))",
                "title"=>"Dni de 8 dígitos y 1 letra en mayúsculas"
                )))
            ->add('nombre',TextType::class, array("label"=>"Nombre","required"=>"required","attr" =>array(
                "class" => "form-name form-control",
                "maxlength"=>"30",
                "pattern"=>"([a-zA-ZñÑáéíóúÁÉÍÓÚüÜ ']{3,30})",
                "title"=>"Nombre de 3 a 30 carácteres"
                )))
            ->add('ape1',TextType::class, array("label"=>"Primer Apellido","required"=>"required","attr" =>array(
                "class" => "form-name form-control",
                "maxlength"=>"30",
                "pattern"=>"([a-zA-ZñÑáéíóúÁÉÍÓÚüÜ ']{3,30})",
                "title"=>"Apellido de 3 a 30 carácteres"
                )))
            ->add('ape2',TextType::class, array("label"=>"Segundo Apellido","required"=>"required","attr" =>array(
                "class" => "form-name form-control",
                "maxlength"=>"30",
                "pattern"=>"([a-zA-ZñÑáéíóúÁÉÍÓÚüÜ ']{3,30})",
                "title"=>"Apellido de 3 a 30 carácteres"
                )))
            ->add('direccion',TextType::class, array("label"=>"Dirección","required"=>"required","attr" =>array(
                "class" => "form-name form-control",
                "maxlength"=>"50",
                "pattern"=>"([a-zA-ZñÑáéíóúÁÉÍÓÚüÜ1234567890º ']{10,50})",
                "title"=>"Dirección de 10 a 50 carácteres"
                )))
            ->add('poblacion',TextType::class, array("label"=>"Población","required"=>"required","attr" =>array(
                "class" => "form-name form-control",
                "maxlength"=>"30",
                "pattern"=>"([a-zA-ZñÑáéíóúÁÉÍÓÚüÜ ']{3,30})",
                "title"=>"Población de 3 a 30 carácteres"
                )))
            ->add('cp',TextType::class, array("label"=>"Código Postal","required"=>"required","attr" =>array(
                "class" => "form-name form-control",
                "maxlength"=>"5",
                "pattern"=>"\d{5}",
                "title"=>"Código de 5 dígitos."
                )))
            ->add('provincia',TextType::class, array("label"=>"Provincia","required"=>"required","attr" =>array(
                "class" => "form-name form-control",
                "maxlength"=>"30",
                "pattern"=>"([a-zA-ZñÑáéíóúÁÉÍÓÚüÜ ']{3,30})",
                "title"=>"Provincia de 3 a 30 carácteres"
                )))
            ->add('tlf',TextType::class, array("label"=>"Telefono de contacto","required"=>"required","attr" =>array(
                "class" => "form-name form-control",
                "maxlength"=>"9",
                "pattern"=>"\d{9}",
                "title"=>"Telefono de 9 dígitos."
                )))
            ->add('mail',EmailType::class, array("label"=>"Correo electrónico","required"=>"required","attr" =>array(
                "class" => "form-name form-control",
                "maxlength"=>"50",
                "title"=>"Email de hasta 50 arácteres."
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
            'data_class' => 'FctBundle\Entity\Alumno'
        ));
    }
}
