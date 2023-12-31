<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ValidarModificarProducto
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        
        $parametrosBody = $request->getParsedBody();
        
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);

        $clavePedido = $parametrosBody['clavePedido'];

        $pedido = Pedido::obtenerPedido($clavePedido);

        if($pedido->clavePedido != null)
        {
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'No perteneces al sector correspondiente'));
            $response->getBody()->write($payload);
        }

        return $response;
    }
}