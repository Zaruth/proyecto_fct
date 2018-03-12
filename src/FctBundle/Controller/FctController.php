<?php

namespace FctBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\Query\ResultSetMapping;
use FctBundle\Entity\Ciclo;
use FctBundle\Entity\Alumno;
use FctBundle\Entity\Profesor;
use FctBundle\Entity\Empresa;
use FctBundle\Entity\Fct;
use FctBundle\Form\CicloType;

class FctController extends Controller {

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
        
        $fct = new Fct();
        $em = $this->getDoctrine()->getEntityManager();
        $fct_repo = $em->getRepository("FctBundle:Fct");

        
        $fct = $fct_repo->getPaginateEntries($num_pag,$per_pag);
        
        $totalitems = count($fct);
        $pageCount = ceil($totalitems/$per_pag);
        
        if($num_pag > $pageCount){
            $num_pag = $pageCount;
            $fct = $fct_repo->getPaginateEntries($num_pag,$per_pag);
        }
        
        $totalitems = count($fct);
        $pageCount = ceil($totalitems/$per_pag);
        
        
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $status = "No tienes acceso.";
            $class = "alert-danger";
            $this->session->getFlashBag()->add("class", $class);
            $this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('fct_homepage');
        } else {
            return $this->render('FctBundle:Fct:listado.html.twig', array(
                "error" => $error,
                "last_username" => $last_username,
                "filas" => $fct,
                "num_pag" => $num_pag,
                "per_pag" => $per_pag,
                "pageCount" => $pageCount
            ));
        }
    }
    /*
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
            $ciclo->setPeriodo($form->get("periodo")->getData());

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
    */

}
