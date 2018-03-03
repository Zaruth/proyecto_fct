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

class ProfesorType extends AbstractType
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
            ->add('nuser',TextType::class, array("label"=>"Nombre de Usuario","required"=>"required","attr" =>array(
                "class" => "form-name form-control",
                "maxlength"=>"20",
                "pattern"=>"([a-zA-Z]{8,20})",
                "title"=>"Usuario de 8 a 20 carácteres sin espacios con letras mayúsculas o minusculas."
                )))
            ->add('pass',PasswordType::class, array("label"=>"Contraseña","required"=>"required","attr" =>array(
                "class" => "form-name form-control",
                "maxlength"=>"20",
                "pattern"=>"^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$",
                "title"=>"Contraseña de 8 a 20 carácteres con al menos 1 dígito, 1 minúscula y 1 mayúscula."
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
            ->add('Guardar',SubmitType::class, array("attr" =>array(
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
            'data_class' => 'FctBundle\Entity\Profesor'
        ));
    }
}
