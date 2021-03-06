<?php

namespace FctBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use FctBundle\Entity\Alumno;
use FctBundle\Form\AlumnoType;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controlador de la entidad alumno
 */
class AlumnoController extends Controller
{
    /**
     * Variable de Sesión de la app
     * @var Session 
     */
    private $session;

    /**
     * Constructor de la variable de Sesión
     */
    public function __construct() {
        $this->session = new Session();
    }
    
    /**
     * Muestra el listado de alumnos
     * @param integer $num_pag
     * @param integer $per_pag
     * @return Vista
     */
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
    
    /**
     * Muestra la ficha de un alumno
     * @param Request $request
     * @param string $nif
     * @return Vista
     */
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
    
    /**
     * Borra un alumno y sus relaciones
     * @param Request $request
     * @param string $nif
     * @return Vista
     * @throws Vista
     */
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
    
    /**
     * Muestra el registro de alumno
     * @param Request $request
     * @return Vista
     */
    public function registroAction(Request $request) {
        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();

        $isValid = false;

        //FORM ALUMNO

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
                    $alumno->setCiclo(null);

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

            //END FORM ALUMNO
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
    
    /**
     * Serializa los datos de la base de datos de alumnos en un archivo XML
     * @return Vista
     */
    public function serializadorAction(){
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(10);
        // Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);

        $serializer = new Serializer($normalizers, $encoders);

        $alumno = new Alumno();
        $em = $this->getDoctrine()->getEntityManager();
        $alumno_repo = $em->getRepository("FctBundle:Alumno");
        
        $alumno = $alumno_repo->findAll();

        $xmlcontent = $serializer->serialize($alumno, 'xml');
        
        $xmlcontent = trim(str_replace('<?xml version="1.0"?>',"",$xmlcontent));
        $xmlcontent = trim(str_replace('<response>',"",$xmlcontent));
        $xmlcontent = trim(str_replace('</response>',"",$xmlcontent));
        
        $response = new Response();
        $response->headers->set('Content-Type', 'xml');
        $response->headers->set('charset','utf-8');
        return $this->render('FctBundle:Alumno:datos_sacados.xml.twig', array('xmlcontent' => $xmlcontent), $response);
    }
}
