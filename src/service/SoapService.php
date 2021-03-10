<?php

namespace App\Services;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SoapService extends AbstractController
{
    public function __construct()
    {
        global $kernel;

        $this->container = $kernel->getContainer();
        // $this->apikey = $this->container->getParameter('apiKeyConexionService');
    }

    public function getApikey()
    {
        return $this->apikey;
    }

    public function getUserId()
    {
    }

    public function body()
    {
        $data_array = [
            '' => 'profile',
        ];

        $data_rol = [
            'developer' => 'rol',
            '36' => 'age',
            'male' => 'sex',
            'zeus_jesus10@hotmail.com' => 'email',
        ];

        $profile_props = [
                  'Jesus Hernandez' => 'nombres',                  
                ];

        $xml = new \SimpleXMLElement('<app/>');
        array_walk_recursive($data_array, [$xml, 'addChild']);
        array_walk_recursive($data_rol, [$xml->profile, 'addChild']);
        array_walk_recursive($profile_props, [$xml->profile, 'addAttribute']);

        // Otra forma
        $xml->profile[0]->addChild('puntuacion', 'success');

        return $xml->asXML();
    }

    public function messageApikeyInvalid()
    {
        $data_array = [
            '' => 'response',
        ];

        $data_response = [
            '500' => 'code',
            'true' => 'error',
          ];

        $profile_props = [
                  'Jesus Hernandez' => 'name-author',
                ];

        $xml = new \SimpleXMLElement('<app/>');
        array_walk_recursive($data_array, [$xml, 'addChild']);
        array_walk_recursive($data_response, [$xml->response, 'addChild']);
        array_walk_recursive($profile_props, [$xml->response, 'addAttribute']);

        // Otra forma
        $xml->response[0]->addChild('message', 'Error apikey is invalid');

        return $xml->asXML();
    }

    public function messageXmlInvalid()
    {
        $data_array = [
            '' => 'response',
        ];

        $data_response = [
            '500' => 'code',
            'true' => 'error',
          ];

        $profile_props = [
                  'Jesus Hernandez' => 'name-author',
                ];

        $xml = new \SimpleXMLElement('<app/>');
        array_walk_recursive($data_array, [$xml, 'addChild']);
        array_walk_recursive($data_response, [$xml->response, 'addChild']);
        array_walk_recursive($profile_props, [$xml->response, 'addAttribute']);

        // Otra forma
        $xml->response[0]->addChild('message', 'Error the XML is Invalid or Break please check');

        return $xml->asXML();
    }

    public function setMessageCustom($code, $message, $err = 'true', $account = null)
    {
        $data_array = [
            '' => 'response',
        ];

        $data_response = [
            $code => 'code',
            $err => 'error',
          ];

        $profile_props = [
                  'Jesus Hernandez' => 'name-author',
                ];

        $xml = new \SimpleXMLElement('<app/>');
        array_walk_recursive($data_array, [$xml, 'addChild']);
        array_walk_recursive($data_response, [$xml->response, 'addChild']);
        array_walk_recursive($profile_props, [$xml->response, 'addAttribute']);

        // Otra forma
        $xml->response[0]->addChild('message', $message);

        if (null != $account) {
            $xml->response[0]->addChild('account', $account['account']);
            $xml->response[0]->addChild('billetera', $account['billetera']);
        }

        return $xml->asXML();
    }

    public function validarCliente($data)
    {
        $isValid = false;
        $dom = new \DOMDocument();
        $dom->loadXML($data);
        $cliente = \simplexml_import_dom($dom);

        if (isset($cliente->nombres) && isset($cliente->email) && isset($cliente->documento) && 
                isset($cliente->cell) &&
                '' != trim($cliente->nombres) &&                
                '' != trim($cliente->email) &&
                '' != trim($cliente->documento) &&                
                '' != trim($cliente->cell)
            ) {
            $isValid = true;
        }

        return $isValid;
    }

    public function clientLoginIsValid($data)
    {
        $isValid = false;
        $dom = new \DOMDocument();
        $dom->loadXML($data);
        $cliente = \simplexml_import_dom($dom);

        if (isset($cliente->email) && isset($cliente->documento) && '' != trim($cliente->email) &&  '' != trim($cliente->documento)) {
            $isValid = true;
        }

        return $isValid;
    }

    public function getDatosCliente($data)
    {
        $dom = new \DOMDocument();
        $dom->loadXML($data);
        $cliente = \simplexml_import_dom($dom);

        return [
            'nombres'   => $cliente->nombres,            
            'email'     => $cliente->email,
            'documento' => $cliente->documento,            
            'cell'      => $cliente->cell,
        ];
    }

    public function getDataLogin($data)
    {
        $dom = new \DOMDocument();
        $dom->loadXML($data);
        $cliente = \simplexml_import_dom($dom);

        return [
            'email'     => $cliente->email,
            'documento' => $cliente->documento,
        ];
    }    
}
