<?php

namespace FctBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use FctBundle\Entity\Profesor;
use FctBundle\Form\ProfesorType;

class ProfesorController extends Controller
{
    private $session;
    
    public function __construct() {
        $this->session=new Session();
    }
    
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();
        
        //FORM PROFESOR
        
        $profesor = new Profesor();
        $form = $this->createForm(ProfesorType::class,$profesor);
        
        $form->handleRequest($request);
        if($form->isSubmitted()){
            if($form->isValid()){
                $profesor = new Profesor();
                $profesor->setNif($form->get("nif")->getData());
                $profesor->setNombre($form->get("nombre")->getData());
                $profesor->setApe1($form->get("ape1")->getData());
                $profesor->setApe2($form->get("ape2")->getData());
                $profesor->setNuser($form->get("nuser")->getData());
                $profesor->setPass($form->get("pass")->getData());
                $profesor->setTlf($form->get("tlf")->getData());
                $profesor->setMail($form->get("mail")->getData());
                $profesor->setRole("ROLE_USER");
                $profesor->setImg(null);

                $em = $this->getDoctrine() -> getEntityManager();
                $em->persist($profesor);
                $flush = $em->flush();
                if($flush==null){
                    $status = "El usuario se ha creado correctamente.";
                }else{
                    $status = "El usuario se ha creado correctamente.";
                }
            }else{
                $status = "No te has registrado correctamente.";
            }

            //END FORM PROFESOR

            $this->session->getFlashBag()->add("status",$status);
        }
        return $this->render('FctBundle:Profesor:login.html.twig', array(
            "error" => $error,
            "last_username" => $last_username,
            "form" => $form->createView()
        ));
    }
}