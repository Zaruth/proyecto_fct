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

class CicloController extends Controller {

    private $session;

    public function __construct() {
        $this->session = new Session();
    }

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

}
