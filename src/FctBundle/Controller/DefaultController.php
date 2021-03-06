<?php

namespace FctBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Controlador por defecto
 */
class DefaultController extends Controller {

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
     * Devuelve la página de inicio
     * @return Vista
     */
    public function indexAction() {
        
        
        $this->iniciaSession();
        
        return $this->render('FctBundle:Default:index.html.twig',array(
                "pais" => $this->session->get('pais'),
                "latitud" => $this->session->get('latitud'),
                "longitud" => $this->session->get('longitud')
            ));
    }
    
    /**
     * Inicia las variables de sesion
     */
    public function iniciaSession() {
        if(!$this->get('session')->has('pais') || !$this->get('session')->has('longitud') || !$this->get('session')->has('latitud')){
            $result = $this->geolocalizacion();
            $this->get('session')->set('pais', $result["ResolveIPResult"]["Country"]);
            $this->get('session')->set('longitud', $result["ResolveIPResult"]["Longitude"]);
            $this->get('session')->set('latitud', $result["ResolveIPResult"]["Latitude"]);
        }
    }
    
    /**
     * Crea una petición con el cliente de SOAP para obetener la geolposición y otros datos.
     * @return array
     */
    public function geolocalizacion(){
        // Config
        $client = new \nusoap_client('http://ws.cdyne.com/ip2geo/ip2geo.asmx?WSDL', 'wsdl');
        $client->soap_defencoding = 'UTF-8';
        $client->decode_utf8 = FALSE;

        // Calls
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        //Oculta la linea de abajo. Es para testear.
        $ip = "84.127.252.103";
        
        $result = $client->call('ResolveIP', array("ipAddress" => $ip, "licenseKey" => "0"));
        return $result;
    }

}
