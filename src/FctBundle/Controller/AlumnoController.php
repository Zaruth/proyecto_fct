<?php

namespace FctBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use FctBundle\Entity\Alumno;
use FctBundle\Form\AlumnoType;

class AlumnoController extends Controller
{
    private $session;

    public function __construct() {
        $this->session = new Session();
    }
    
    public function listadoAction($num_pag, $per_pag) {
        if($num_pag < 1){
            $num_pag = 1;
        }
        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();
        
        $alumnos = new Alumno();
        $em = $this->getDoctrine()->getEntityManager();
        $alumno_repo = $em->getRepository("FctBundle:Alumno");

        
        $alumnos = $alumno_repo->getPaginateEntries($num_pag,$per_pag);
        
        $totalitems = count($alumnos);
        $pageCount = ceil($totalitems/$per_pag);
        
        if($num_pag > $pageCount){
            $num_pag = $pageCount;
            $alumnos = $alumno_repo->getPaginateEntries($num_pag,$per_pag);
        }
        
        $totalitems = count($alumnos);
        $pageCount = ceil($totalitems/$per_pag);
        
        
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $status = "No tienes acceso.";
            $class = "alert-danger";
            $this->session->getFlashBag()->add("class", $class);
            $this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('fct_homepage');
        } else {
            return $this->render('FctBundle:Alumno:listado.html.twig', array(
                "error" => $error,
                "last_username" => $last_username,
                "usuarios" => $alumnos,
                "num_pag" => $num_pag,
                "per_pag" => $per_pag,
                "pageCount" => $pageCount
            ));
        }
    }
    
    public function fichaAction(Request $request, $nif) {

        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();

        $alumno = new Alumno();
        $em = $this->getDoctrine()->getEntityManager();
        $alumno_repo = $em->getRepository("FctBundle:Alumno");

        $alumno = $alumno_repo->findOneBy(array("nif" => $nif));

        if (count($alumno) == 0) {
            $status = "No existe ese usuario :(";
            $class = "alert-danger";
            $this->session->getFlashBag()->add("class", $class);
            $this->session->getFlashBag()->add("status", $status);
        }

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('listado_alum');
        } else {
            return $this->render('FctBundle:Alumno:perfil.html.twig', array(
                        "error" => $error,
                        "last_username" => $last_username,
                        "usuario" => $alumno
            ));
        }
    }
    
    public function deleteAction(Request $request, $nif) {
        $salir = false;
        $user = $this->getUser();
        if ($user->getNif() == $nif) {
            $this->session->invalidate();
        }


        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();

        $alumno = new Alumno();
        $em = $this->getDoctrine()->getEntityManager();
        $alumno_repo = $em->getRepository("FctBundle:Alumno");

        $alumno = $alumno_repo->findOneBy(array("nif" => $nif));
        $em->remove($alumno);
        $em->flush();

        $status = "Alumno borrado";
        $class = "alert-danger";
        $this->session->getFlashBag()->add("class", $class);
        $this->session->getFlashBag()->add("status", $status);


        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        } else {
            return $this->redirectToRoute('listado_alum');
        }
    }
    
    public function registroAction(Request $request) {
        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();

        $isValid = false;

        //FORM AALUMNO

        $alumno = new Alumno();
        $form = $this->createForm(AlumnoType::class, $alumno);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $alumno_repo = $em->getRepository("FctBundle:Alumno");
                $mail_alumno = $alumno_repo->findOneBy(array("mail" => $form->get("mail")->getData()));

                if (count($mail_alumno) == 0) {
                    $alumno = new Alumno();
                    $alumno->setNif($form->get("nif")->getData());
                    $alumno->setNombre($form->get("nombre")->getData());
                    $alumno->setApe1($form->get("ape1")->getData());
                    $alumno->setApe2($form->get("ape2")->getData());
                    $alumno->setDireccion($form->get("direccion")->getData());
                    $alumno->setPoblacion($form->get("poblacion")->getData());
                    $alumno->setCp($form->get("cp")->getData());
                    $alumno->setProvincia($form->get("provincia")->getData());
                    $alumno->setTlf($form->get("tlf")->getData());
                    $alumno->setMail($form->get("mail")->getData());
                    $alumno->setImg(null);

                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($alumno);
                    $flush = $em->flush();
                    if ($flush == null) {
                        $isValid = true;
                        $status = "¡Usuario creado con éxito :)!";
                        $class = "alert-success";
                    }
                } else {
                    $status = "Ese correo ya está siendo usado.";
                    $class = "alert-danger";
                }
            } else {
                $status = "No has registrado correctamente al alumno.";
                $class = "alert-danger";
            }

            //END FORM PROFESOR
            $this->session->getFlashBag()->add("class", $class);
            $this->session->getFlashBag()->add("status", $status);
        }
        if ($isValid) {
            return $this->redirectToRoute('listado_alum');
        } else {
            return $this->render('FctBundle:Alumno:registro.html.twig', array(
                "error" => $error,
                "last_username" => $last_username,
                "form" => $form->createView()
            ));
        }
    }
}
