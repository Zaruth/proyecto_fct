<?php

namespace FctBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use FctBundle\Entity\Profesor;
use FctBundle\Form\ProfesorType;

class ProfesorController extends Controller {

    private $session;

    public function __construct() {
        $this->session = new Session();
    }
    
    public function panel_maestroAction(Request $request) {
        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();
        
        if ($this->getUser()->getRole() != 'ROLE_SUPER_ADMIN') {
            $status = "No tienes acceso al panel maestro.";
            $class = "alert-danger";
            $this->session->getFlashBag()->add("class", $class);
            $this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('fct_homepage');
        } else {
            return $this->render('FctBundle:Profesor:panel.html.twig', array(
                "error" => $error,
                "last_username" => $last_username,
            ));
        }
    }
    
    public function funcion_panelAction(Request $request, $id, $numero) {
        if ($id != null) {
            switch ($id){
                case 1:
                    $this->generar_profesores($numero);
                    $status = "Datos de prueba generados.";
                    $class = "alert-success";
                    break;
                case 2:
                    $this->borrar_profesores();
                    $status = "Datos borrados con éxito.";
                    $class = "alert-success";
                    break;
                case 3:
                    break;
                default:
                    break;
            }
        }
        
        if ($this->getUser()->getRole() != 'ROLE_SUPER_ADMIN') {
            $status = "No tienes acceso al panel maestro.";
            $class = "alert-danger";
            $this->session->getFlashBag()->add("class", $class);
            $this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('fct_homepage');
        } else {
            $this->session->getFlashBag()->add("class", $class);
            $this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('panel_maestro');
        }
    }

    public function listadoAction(Request $request) {

        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();

        $profesores = new Profesor();
        $em = $this->getDoctrine()->getEntityManager();
        $profesor_repo = $em->getRepository("FctBundle:Profesor");

        $profesores = $profesor_repo->findAll();

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('fct_homepage');
        } else {
            return $this->render('FctBundle:Profesor:listado.html.twig', array(
                "error" => $error,
                "last_username" => $last_username,
                "usuarios" => $profesores
            ));
        }
    }

    public function perfilAction(Request $request, $nif) {

        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();

        $profesor = new Profesor();
        $em = $this->getDoctrine()->getEntityManager();
        $profesor_repo = $em->getRepository("FctBundle:Profesor");

        $profesor = $profesor_repo->findOneBy(array("nif" => $nif));

        if (count($profesor) == 0) {
            $status = "No existe ese usuario :(";
            $class = "alert-danger";
            $this->session->getFlashBag()->add("class", $class);
            $this->session->getFlashBag()->add("status", $status);
        }

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('listado_prof');
        } else {
            return $this->render('FctBundle:Profesor:perfil.html.twig', array(
                        "error" => $error,
                        "last_username" => $last_username,
                        "usuario" => $profesor
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

        $profesor = new Profesor();
        $em = $this->getDoctrine()->getEntityManager();
        $profesor_repo = $em->getRepository("FctBundle:Profesor");

        $profesor = $profesor_repo->findOneBy(array("nif" => $nif));
        $em->remove($profesor);
        $em->flush();

        $status = "Profesor borrado";
        $class = "alert-danger";
        $this->session->getFlashBag()->add("class", $class);
        $this->session->getFlashBag()->add("status", $status);


        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        } else {
            return $this->redirectToRoute('listado_prof');
        }
    }

    public function loginAction(Request $request) {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('fct_homepage');
        } 
        
        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $response = $this->forward('FctBundle:Default:index', array(
                "error" => $error,
                "last_username" => $last_username
            ));
            return $response;
        }

        return $this->render('FctBundle:Profesor:login.html.twig', array(
            "error" => $error,
            "last_username" => $last_username
        ));
    }

    public function registroAction(Request $request) {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();

        $isValid = false;

        //FORM PROFESOR

        $profesor = new Profesor();
        $form = $this->createForm(ProfesorType::class, $profesor);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $profesor_repo = $em->getRepository("FctBundle:Profesor");
                $mail_prof = $profesor_repo->findOneBy(array("mail" => $form->get("mail")->getData()));

                if (count($mail_prof) == 0) {
                    $profesor = new Profesor();
                    $profesor->setNif($form->get("nif")->getData());
                    $profesor->setNombre($form->get("nombre")->getData());
                    $profesor->setApe1($form->get("ape1")->getData());
                    $profesor->setApe2($form->get("ape2")->getData());
                    $profesor->setNuser($form->get("nuser")->getData());

                    $factory = $this->get("security.encoder_factory");
                    $encoder = $factory->getEncoder($profesor);
                    $password = $encoder->encodePassword($form->get("pass")->getData(), $profesor->getSalt());

                    $profesor->setPass($password);

                    $profesor->setTlf($form->get("tlf")->getData());
                    $profesor->setMail($form->get("mail")->getData());
                    $profesor->setRole("ROLE_USER");
                    $profesor->setImg(null);

                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($profesor);
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
                $status = "No te has registrado correctamente.";
                $class = "alert-danger";
            }

            //END FORM PROFESOR
            $this->session->getFlashBag()->add("class", $class);
            $this->session->getFlashBag()->add("status", $status);
        }
        if ($isValid) {
            return $this->render('FctBundle:Default:index.html.twig', array(
                "error" => $error,
                "last_username" => $last_username
            ));
        } else {
            return $this->render('FctBundle:Profesor:registro.html.twig', array(
                "error" => $error,
                "last_username" => $last_username,
                "form" => $form->createView()
            ));
        }
    }
    
    /**
     * Genera un numero de profesores de prueba
     * con nombre de usuario y contraseña idénticos.
     * También borra los usuarios de prueba anteriores.
     * @param int $numero
     */
    public function generar_profesores($numero) {
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        
        $qb->select('p.nif')
            ->from('FctBundle\Entity\Profesor', 'p')
            ->where($qb->expr()->like('p.nuser', "'%user%'"));
        
        $query = $qb->getQuery();
        $results = $query->getResult();
        
        $profesor_repo = $em->getRepository("FctBundle:Profesor");
        
        for($i = 0;$i < count($results);$i++){
            $profesor = $profesor_repo->findOneBy(array("nif" => $results[$i]["nif"]));
            $em->remove($profesor);
        }
        $flush = $em->flush();
        
        for($i = 0;$i < $numero;$i++){
            $profesor = new Profesor();
            $profesor->setNif($i);
            $profesor->setNombre("user".$i);
            $profesor->setApe1("user".$i);
            $profesor->setApe2("user".$i);
            $profesor->setNuser("user".$i);

            $factory = $this->get("security.encoder_factory");
            $encoder = $factory->getEncoder($profesor);
            $password = $encoder->encodePassword("user".$i, $profesor->getSalt());

            $profesor->setPass($password);

            $profesor->setTlf($i);
            $profesor->setMail("user".$i."@gmail.com");
            $profesor->setRole("ROLE_USER");
            $profesor->setImg(null);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($profesor);
            $flush = $em->flush();
        }
    }
    
    /**
     * Borra todos los profesores de la base de datos excepto
     * los que posean el rol SUPER_ADMIN
     */
    public function borrar_profesores() {
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        
        $qb->select('p.nif')
            ->from('FctBundle\Entity\Profesor', 'p')
            ->where("p.role != 'ROLE_SUPER_ADMIN'");
        
        $query = $qb->getQuery();
        $results = $query->getResult();
        
        $profesor_repo = $em->getRepository("FctBundle:Profesor");
        
        for($i = 0;$i < count($results);$i++){
            $profesor = $profesor_repo->findOneBy(array("nif" => $results[$i]["nif"]));
            $em->remove($profesor);
        }
        $flush = $em->flush();
    }
}
