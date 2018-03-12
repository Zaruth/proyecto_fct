<?php

namespace FctBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use FctBundle\Entity\Empresa;
use FctBundle\Form\EmpresaType;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Response;

class EmpresaController extends Controller
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
        
        $empresas = new Empresa();
        $em = $this->getDoctrine()->getEntityManager();
        $empresa_repo = $em->getRepository("FctBundle:Empresa");

        
        $empresas = $empresa_repo->getPaginateEntries($num_pag,$per_pag);
        
        $totalitems = count($empresas);
        $pageCount = ceil($totalitems/$per_pag);
        
        if($num_pag > $pageCount){
            $num_pag = $pageCount;
            $empresas = $empresa_repo->getPaginateEntries($num_pag,$per_pag);
        }
        
        $totalitems = count($empresas);
        $pageCount = ceil($totalitems/$per_pag);
        
        
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $status = "No tienes acceso.";
            $class = "alert-danger";
            $this->session->getFlashBag()->add("class", $class);
            $this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('fct_homepage');
        } else {
            return $this->render('FctBundle:Empresa:listado.html.twig', array(
                "error" => $error,
                "last_username" => $last_username,
                "usuarios" => $empresas,
                "num_pag" => $num_pag,
                "per_pag" => $per_pag,
                "pageCount" => $pageCount
            ));
        }
    }
    
    public function fichaAction(Request $request, $cif) {

        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();

        $empresa = new Empresa();
        $em = $this->getDoctrine()->getEntityManager();
        $empresa_repo = $em->getRepository("FctBundle:Empresa");

        $empresa = $empresa_repo->findOneBy(array("cif" => $cif));

        if (count($empresa) == 0) {
            $status = "No existe ese usuario :(";
            $class = "alert-danger";
            $this->session->getFlashBag()->add("class", $class);
            $this->session->getFlashBag()->add("status", $status);
        }

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('listado_emp');
        } else {
            return $this->render('FctBundle:Empresa:perfil.html.twig', array(
                        "error" => $error,
                        "last_username" => $last_username,
                        "usuario" => $empresa
            ));
        }
    }
    
    public function deleteAction(Request $request, $cif) {
        $salir = false;

        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();

        $empresa = new Empresa();
        $em = $this->getDoctrine()->getEntityManager();
        $empresa_repo = $em->getRepository("FctBundle:Empresa");

        $empresa = $empresa_repo->findOneBy(array("cif" => $cif));
        $em->remove($empresa);
        $em->flush();

        $status = "Empresa borrada";
        $class = "alert-danger";
        $this->session->getFlashBag()->add("class", $class);
        $this->session->getFlashBag()->add("status", $status);


        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        } else {
            return $this->redirectToRoute('listado_emp');
        }
    }
    
    public function registroAction(Request $request) {
        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();

        $isValid = false;

        //FORM EMPRESA

        $empresa = new Empresa();
        $form = $this->createForm(EmpresaType::class, $empresa);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $empresa_repo = $em->getRepository("FctBundle:Empresa");
                $mail_empresa = $empresa_repo->findOneBy(array("mail" => $form->get("mail")->getData()));

                if (count($mail_empresa) == 0) {
                    $empresa = new Empresa();
                    $empresa->setCif($form->get("cif")->getData());
                    $empresa->setNombre($form->get("nombre")->getData());
                    $empresa->setDireccion($form->get("direccion")->getData());
                    $empresa->setPoblacion($form->get("poblacion")->getData());
                    $empresa->setCp($form->get("cp")->getData());
                    $empresa->setProvincia($form->get("provincia")->getData());
                    $empresa->setTlf($form->get("tlf")->getData());
                    $empresa->setMail($form->get("mail")->getData());
                    $empresa->setTutorLaboral(null);

                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($empresa);
                    $flush = $em->flush();
                    if ($flush == null) {
                        $isValid = true;
                        $status = "¡Empresa creada con éxito :)!";
                        $class = "alert-success";
                    }
                } else {
                    $status = "Ese correo ya está siendo usado.";
                    $class = "alert-danger";
                }
            } else {
                $status = "No has registrado correctamente la empresa.";
                $class = "alert-danger";
            }

            //END FORM EMPRESA
            $this->session->getFlashBag()->add("class", $class);
            $this->session->getFlashBag()->add("status", $status);
        }
        if ($isValid) {
            return $this->redirectToRoute('listado_emp');
        } else {
            return $this->render('FctBundle:Empresa:registro.html.twig', array(
                "error" => $error,
                "last_username" => $last_username,
                "form" => $form->createView()
            ));
        }
    }
    
    public function serializadorAction(){
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
        
        $empresa = new Empresa();
        $em = $this->getDoctrine()->getEntityManager();
        $empresa_repo = $em->getRepository("FctBundle:Empresa");
        
        $empresa = $empresa_repo->findAll();

        $xmlcontent = $serializer->serialize($empresa, 'xml');
        
        return $this->render('FctBundle:Fct:datos_sacados.xml.twig', array(
                "xmlcontent" => $xmlcontent,
            ));
    }
}
