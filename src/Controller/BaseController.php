<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Cliente;
use App\Entity\Billetera;
use App\Services\SoapService;


class BaseController extends AbstractController
{

    
    private $SoapService = null;
    

    public function __construct()
    {
        $this->SoapService = new SoapService();        
    }


    /**
     * @Route("/api/cliente", name="cliente")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $cliente = $em->getRepository(Cliente::class)->getClientes();
        return $this->Json($cliente);       
    }


     /**
     * @Route("/api/registrar", methods={"POST"}, name="registrar")
     */
    public function registarCliente(Request $request)
    {

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=UTF-8');
        $em = $this->getDoctrine()->getManager();

        $body = $request->getContent();
        $dom = new \DOMDocument();
        $xml = @$dom->loadXML($body);

        if($this->SoapService->validarCliente($body) && $xml){

            $data = $this->SoapService->getDatosCliente($body);

            $validarDocumento = $em->getRepository(Cliente::class)->findBy(['documento' => $data['documento']]);
            if(count($validarDocumento) > 0){
                $response->setContent($this->SoapService->setMessageCustom(406, 'Ya existe un cliente registrado con el mismo nro de documento'));
                $response->setStatusCode(406, 'Error Documento');
                return $response;
            }

            $validarEmail = $em->getRepository(Cliente::class)->findBy(['email' => $data['email']]);
            if(count($validarEmail) > 0){
                $response->setContent($this->SoapService->setMessageCustom(406, 'Ya existe un cliente registrado con ese email'));
                $response->setStatusCode(406, 'Error Email');
                return $response;
            }

            
            $cliente = new Cliente();
            $billetera = new Billetera();
            
            $cliente->setNombres($data['nombres']);
            $cliente->setEmail($data['email']);
            $cliente->setDocumento($data['documento']);
            $cliente->setCell($data['cell']);  
            
            $billetera->setCliente($cliente);
            $billetera->setBalance(0.00);
            $billetera->setDinamicToken(random_int(100000, 999999));            
            
            $em->persist($cliente);
            $em->persist($billetera);
            $em->flush();

            $response->setContent($this->SoapService->setMessageCustom(200, 'Registro de cliente exitoso!!!', 'false'));
            $response->setStatusCode(200, 'registro cliente exitoso!!!');

        }else{
            $response->setContent($this->SoapService->messageXmlInvalid());
            $response->setStatusCode(400, 'Formato invalido');
        }

        return $response;
    }


    /**
     * @Route("/api/recarga", methods={"POST"}, name="recarga")
     */
    public function recarga(Request $request)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $em = $this->getDoctrine()->getManager();
        
        $data = json_decode($request->getContent(), true);
        $billetera = $em->getRepository(Billetera::class)->findBy(['id' => $data['billetera_id']]);

        // Vamos a validar que los datos enviados sean los correctos
        if (isset($billetera[0])) {
            $cliente = $billetera[0]->getCliente();
            $documento = intval($data['documento']);
            $cell = $data['cell'];
            $monto = floatval($data['monto']);

            if ($cliente->getDocumento() == $documento && $cliente->getCell() == $cell) {
                $billetera[0]->setDinamicToken(random_int(100000, 999999));
                $billetera[0]->setBalance(floatval($billetera[0]->getBalance() + $monto));

                $em->persist($billetera[0]);
                $em->flush();

                $response = new JsonResponse([
                'code'          => 200,
                'balance'       => $billetera[0]->getBalance(),
                'message'       => 'Regarca exitosa!!!',
                'transsaccion'  => true,
              ]);
            }else{
                $response = new JsonResponse(['code' => 404, 'message' => 'los datos no coinciden']);
            }
        }else{
            $response = new JsonResponse(['code' => 404, 'message' => 'Billetera no existe']);
        }

        return $response; 

    }

    /**
    * @Route("/api/token", methods={"POST"}, name="token")
    */
    public function getToken(Request $request)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);
        $billetera = $em->getRepository(Billetera::class)->findBy(['id' => $data['billetera_id']]);

       
        if (isset($billetera[0])) {
            $billetera[0]->setDinamicToken(random_int(100000, 999999));

            $em->persist($billetera[0]);
            $em->flush();

            $response = new JsonResponse(['code' => 200,'message' => $billetera[0]->getDinamicToken()]);
        } else {
            $response = new JsonResponse(['code' => 404, 'message' => 'no existe la billetera']);
        } 

        return $response;
    }


    /**
    * @Route("/api/procesar", methods={"POST"}, name="procesar")
    */
    public function procesar(Request $request)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);
        $billetera = $em->getRepository(Billetera::class)->findBy(['id' => $data['billetera_id']]);

        
            if (isset($billetera[0])) {

                $code = intval($data['code']);
                $monto = floatval($data['monto']);
                

                if ($billetera[0]->getDinamicToken() === $code) {
                    if (floatval($billetera[0]->getBalance()) >= $monto) {
                        $billetera[0]->setDinamicToken(random_int(100000, 999999));
                        $billetera[0]->setBalance(floatval($billetera[0]->getBalance() - $monto));

                        $em->persist($billetera[0]);
                        $em->flush();

                        $response = new JsonResponse([
                        'code'          => 200,
                        'message'       => 'Pago realizado',
                        'balance'       => $billetera[0]->getBalance(),
                        'transsaccion'  => true,
                ]);
                    } else {
                        $response = new JsonResponse(['code' => 404, 'message' => 'saldo insuficiente']);
                    }
                } else {
                    $response = new JsonResponse(['code' => 404, 'message' => 'codigo invalido']);
                }
            } else {
                $response = new JsonResponse(['code' => 404, 'message' => 'billetera no existe']);
            }       

        return $response;
    }


    /**
    * @Route("/api/billetera/{id}", methods={"GET"}, name="billetera")
    */
    public function getBilletera(int $id)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $em = $this->getDoctrine()->getManager();
        
        $billetera = $em->getRepository(Billetera::class)->findBy(['id' => $id]);        
        if (isset($billetera[0])) {
            $response = new JsonResponse(['billetera' => $billetera[0]->getBalance()]);
        } else {
            $response = new JsonResponse(['error' => 404, 'message' => 'La billetera no existe']);
        }        

        return $response;
    }

}
