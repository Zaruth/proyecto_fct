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
    
    public function perfilAction(Request $request){
        
        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();
        
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }else{
            return $this->render('FctBundle:Profesor:perfil.html.twig', array(
                "error" => $error,
                "last_username" => $last_username
            ));
        }
    }
    
    public function loginAction(Request $request){
        
        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();
        
        return $this->render('FctBundle:Profesor:login.html.twig', array(
            "error" => $error,
            "last_username" => $last_username
        ));
    }
    
    public function registroAction(Request $request)
    {
        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();
        
        $isValid = false;
        
        //FORM PROFESOR
        
        $profesor = new Profesor();
        $form = $this->createForm(ProfesorType::class,$profesor);
        
        $form->handleRequest($request);
        if($form->isSubmitted()){
            if($form->isValid()){
                $em = $this->getDoctrine()->getEntityManager();
                $profesor_repo = $em->getRepository("FctBundle:Profesor");
                $mail_prof = $profesor_repo->findOneBy(array("mail"=>$form->get("mail")->getData()));
                
                if(count($mail_prof) == 0){
                   $profesor = new Profesor();
                   $profesor->setNif($form->get("nif")->getData());
                   $profesor->setNombre($form->get("nombre")->getData());
                   $profesor->setApe1($form->get("ape1")->getData());
                   $profesor->setApe2($form->get("ape2")->getData());
                   $profesor->setNuser($form->get("nuser")->getData());

                   $factory = $this->get("security.encoder_factory");
                   $encoder = $factory->getEncoder($profesor);
                   $password = $encoder->encodePassword($form->get("pass")->getData(),$profesor->getSalt());

                   $profesor->setPass($password);

                   $profesor->setTlf($form->get("tlf")->getData());
                   $profesor->setMail($form->get("mail")->getData());
                   $profesor->setRole("ROLE_USER");
                   $profesor->setImg(null);

                   $em = $this->getDoctrine() -> getEntityManager();
                   $em->persist($profesor);
                   $flush = $em->flush();
                   if($flush==null){
                       $isValid = true;
                       $status = "¡Usuario creado con éxito :)!";
                       $class = "alert-success";
                   }
                }else{
                   $status = "Ese correo ya está siendo usado.";
                   $class = "alert-danger";
                }
            }else{
                $status = "No te has registrado correctamente.";
                $class = "alert-danger";
            }

            //END FORM PROFESOR
            $this->session->getFlashBag()->add("class",$class);
            $this->session->getFlashBag()->add("status",$status);
        }
        if ($isValid){
            return $this->render('FctBundle:Default:index.html.twig', array(
                "error" => $error,
                "last_username" => $last_username
            ));
        }else{
            return $this->render('FctBundle:Profesor:registro.html.twig', array(
                "error" => $error,
                "last_username" => $last_username,
                "form" => $form->createView()
            ));
        }
    }
}