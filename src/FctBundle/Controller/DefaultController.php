<?php

namespace FctBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    public function indexAction() {
        
        $result = $this->geolocalizacion();
        
        return $this->render('FctBundle:Default:index.html.twig',array(
                "result" => $result
            ));
    }
    
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
        
        $ip = "78.136.117.23";
        
        $result = $client->call('ResolveIP', array("ipAddress" => $ip, "licenseKey" => "0"));
        return $result;
    }

}
