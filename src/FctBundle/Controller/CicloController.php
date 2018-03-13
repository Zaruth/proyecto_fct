<?php

namespace FctBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\Query\ResultSetMapping;
use FctBundle\Entity\Ciclo;
use FctBundle\Entity\Alumno;
use FctBundle\Entity\Profesor;
use FctBundle\Form\CicloType;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controlador de la entidad ciclo
 */
class CicloController extends Controller {

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
     * Muestra el listado de ciclos y sus alumnos.
     * @param integer $id
     * @return Vista
     */
    public function listadoAction($id) {

        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();

        $ciclo = new Ciclo();
        $ciclos = new Ciclo();
        $alumnos = new Alumno();

        $em = $this->getDoctrine()->getEntityManager();
        $ciclo_repo = $em->getRepository("FctBundle:Ciclo");
        $ciclos = $ciclo_repo->findAll();

        if ($id != null) {
            $ciclo = $ciclo_repo->findOneBy(array("id" => $id));
            if ($ciclo != null) {
                $em = $this->getDoctrine()->getEntityManager();
                $alumnos_repo = $em->getRepository("FctBundle:Alumno");
                $alumnos = $alumnos_repo->findBy(array("ciclo" => $id));
            }
        } else {
            $ciclo = $ciclo_repo->findAll();
            foreach ($ciclo as $ci) {
                $ciclo = $ci;
                $id = $ci->getId();
                $em = $this->getDoctrine()->getEntityManager();
                $alumnos_repo = $em->getRepository("FctBundle:Alumno");
                $alumnos = $alumnos_repo->findBy(array("ciclo" => $ci->getId()));
                break;
            }
        }

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('fct_homepage');
        } else {
            if ($ciclo == null && count($ciclos) != 0) {
                $status = "No existe ese ciclo.";
                $class = "alert-danger";
                $this->session->getFlashBag()->add("class", $class);
                $this->session->getFlashBag()->add("status", $status);
                return $this->render('FctBundle:Ciclo:listado.html.twig', array(
                            "error" => $error,
                            "last_username" => $last_username,
                            "alumnos" => $alumnos,
                            "ciclo" => $ciclo,
                            "ciclos" => $ciclos,
                            "id" => $id
                ));
            } else {
                return $this->render('FctBundle:Ciclo:listado.html.twig', array(
                            "error" => $error,
                            "last_username" => $last_username,
                            "alumnos" => $alumnos,
                            "ciclo" => $ciclo,
                            "ciclos" => $ciclos,
                            "id" => $id
                ));
            }
        }
    }
    
    /**
     * Muestra la ficha de datos de un ciclo.
     * @param Request $request
     * @param integer $id
     * @return Vista
     */
    public function fichaAction(Request $request, $id) {

        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();

        $ciclo = new Ciclo();

        $em = $this->getDoctrine()->getEntityManager();
        $ciclo_repo = $em->getRepository("FctBundle:Ciclo");

        $ciclo = $ciclo_repo->findOneBy(array("id" => $id));

        $alumno_repo = $em->getRepository("FctBundle:Alumno");
        $num_alumnos = count($alumno_repo->findBy(array("ciclo" => $id)));


        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('fct_homepage');
        } else {
            if (count($ciclo) == 0) {
                $status = "No existe ese ciclo :(";
                $class = "alert-danger";
                $this->session->getFlashBag()->add("class", $class);
                $this->session->getFlashBag()->add("status", $status);
                return $this->redirectToRoute('listado_ciclo');
            } else {
                return $this->render('FctBundle:Ciclo:perfil.html.twig', array(
                            "error" => $error,
                            "last_username" => $last_username,
                            "usuario" => $ciclo,
                            "num_alumnos" => $num_alumnos
                ));
            }
        }
    }
    
    /**
     * Borra un ciclo y sus relaciones
     * @param Request $request
     * @param integer $id
     * @return Vista
     * @throws Vista
     */
    public function deleteAction(Request $request, $id) {
        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();

        $ciclo = new Ciclo();
        $alumnos = new Alumno();
        $profesores = new Profesor();

        $em = $this->getDoctrine()->getEntityManager();
        $ciclo_repo = $em->getRepository("FctBundle:Ciclo");
        $alumno_repo = $em->getRepository("FctBundle:Alumno");
        $profesor_repo = $em->getRepository("FctBundle:Profesor");
        
        $ciclo = $ciclo_repo->findOneBy(array("id" => $id));
        $alumnos = $alumno_repo->findBy(array("ciclo" => $id));
        $profesores = $profesor_repo->findAll();
        
        foreach ($alumnos as $alumno){
            $alumno->setCiclo(null);
        }
        
        foreach ($profesores as $profesor){
            $profesor->getCiclos()->removeElement($ciclo);
        }

        $em->remove($ciclo);
        $em->flush();

        $status = "Ciclo borrado";
        $class = "alert-danger";
        $this->session->getFlashBag()->add("class", $class);
        $this->session->getFlashBag()->add("status", $status);


        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        } else {
            return $this->redirectToRoute('listado_ciclo');
        }
    }
    
    /**
     * Registra un ciclo
     * @param Request $request
     * @return Vista
     */
    public function registroAction(Request $request) {
        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();

        $isValid = false;

        //FORM CICLO

        $ciclo = new Ciclo();
        $form = $this->createForm(CicloType::class, $ciclo);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $ciclo->setAbr($form->get("abr")->getData());
            $ciclo->setGrado($form->get("grado")->getData());
            $ciclo->setHoras($form->get("horas")->getData());
            $ciclo->setNombre($form->get("nombre")->getData());

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($ciclo);
            $flush = $em->flush();
            if ($flush == null) {
                $isValid = true;
                $status = "¡Ciclo creado con éxito :)!";
                $class = "alert-success";
            } else {
                $status = "No has registrado correctamente la empresa.";
                $class = "alert-danger";
            }
            //END FORM CICLO

            $this->session->getFlashBag()->add("class", $class);
            $this->session->getFlashBag()->add("status", $status);
        }

        if ($isValid == false) {
            return $this->render('FctBundle:Ciclo:registro.html.twig', array(
                        "error" => $error,
                        "last_username" => $last_username,
                        "form" => $form->createView()
            ));
        } else {
            return $this->redirectToRoute('listado_ciclo');
        }
    }
    
    /**
     * Serializa los datos de la base de datos de ciclos en un archivo XML
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

        $ciclo = new Ciclo();
        $em = $this->getDoctrine()->getEntityManager();
        $ciclo_repo = $em->getRepository("FctBundle:Ciclo");
        
        $ciclo = $ciclo_repo->findAll();

        $xmlcontent = $serializer->serialize($ciclo, 'xml');
        
        $xmlcontent = trim(str_replace('<?xml version="1.0"?>',"",$xmlcontent));
        $xmlcontent = trim(str_replace('<response>',"",$xmlcontent));
        $xmlcontent = trim(str_replace('</response>',"",$xmlcontent));
        
        $response = new Response();
        $response->headers->set('Content-Type', 'xml');
        $response->headers->set('charset','utf-8');
        return $this->render('FctBundle:Ciclo:datos_sacados.xml.twig', array('xmlcontent' => $xmlcontent), $response);
    }
}
