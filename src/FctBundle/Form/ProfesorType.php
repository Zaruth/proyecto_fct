<?php

namespace FctBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
                )))
            ->add('nombre',TextType::class, array("label"=>"Nombre","required"=>"required","attr" =>array(
                "class" => "form-name form-control",
                )))
            ->add('ape1',TextType::class, array("label"=>"Primer Apellido","required"=>"required","attr" =>array(
                "class" => "form-surname form-control",
                )))
            ->add('ape2',TextType::class, array("label"=>"Segundo Apellido","required"=>"required","attr" =>array(
                "class" => "form-surname form-control",
                )))
            ->add('nuser',TextType::class, array("label"=>"Nombre de Usuario","required"=>"required","attr" =>array(
                "class" => "form-name form-control",
                )))
            ->add('pass',PasswordType::class, array("label"=>"Contraseña","required"=>"required","attr" =>array(
                "class" => "form-password form-control",
                )))
            ->add('tlf',TextType::class, array("label"=>"Telefono de contacto","required"=>"required","attr" =>array(
                "class" => "form-name form-control",
                )))
            ->add('mail',EmailType::class, array("label"=>"Correo electrónico","required"=>"required","attr" =>array(
                "class" => "form-email form-control",
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
