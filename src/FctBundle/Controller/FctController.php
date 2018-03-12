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
use FctBundle\Form\FctType;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Response;

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

    public function deleteAction(Request $request, $id) {
        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();

        $fct = new Fct();

        $em = $this->getDoctrine()->getEntityManager();
        $fct_repo = $em->getRepository("FctBundle:Fct");
        
        $fct = $fct_repo->findOneBy(array("id" => $id));
        $em->remove($fct);
        $em->flush();

        $status = "Fct borrada";
        $class = "alert-danger";
        $this->session->getFlashBag()->add("class", $class);
        $this->session->getFlashBag()->add("status", $status);


        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        } else {
            return $this->redirectToRoute('listado_fct');
        }
    }
    
    public function registroAction(Request $request) {
        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();

        $isValid = false;

        //FORM FCT

        $fct = new Fct();
        $form = $this->createForm(FctType::class, $fct);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $fct->setAlumno($form->get("alumno")->getData());
            $fct->setProfesor($form->get("profesor")->getData());
            $fct->setEmpresa($form->get("empresa")->getData());
            $fct->setPeriodo($form->get("periodo")->getData());
            $fct->setAnyo($form->get("anyo")->getData());

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($fct);
            $flush = $em->flush();
            if ($flush == null) {
                $isValid = true;
                $status = "¡FCT creada con éxito :)!";
                $class = "alert-success";
            } else {
                $status = "No has registrado correctamente la FCT.";
                $class = "alert-danger";
            }
            //END FORM FCT

            $this->session->getFlashBag()->add("class", $class);
            $this->session->getFlashBag()->add("status", $status);
        }

        if ($isValid == false) {
            return $this->render('FctBundle:Fct:registro.html.twig', array(
                        "error" => $error,
                        "last_username" => $last_username,
                        "form" => $form->createView()
            ));
        } else {
            return $this->redirectToRoute('listado_fct');
        }
    }
    
    public function serializadorAction(){
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
        
        $fct = new Fct();
        $em = $this->getDoctrine()->getEntityManager();
        $fct_repo = $em->getRepository("FctBundle:Fct");
        
        $fct = $fct_repo->findAll();

        $xmlcontent = $serializer->serialize($fct, 'xml');
        
        return $this->render('FctBundle:Fct:datos_sacados.xml.twig', array(
                "xmlcontent" => $xmlcontent,
            ));
    }
}
