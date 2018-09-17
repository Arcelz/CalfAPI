<?php
/**
 * Created by PhpStorm.
 * User: Brunno
 * Date: 06/09/2018
 * Time: 22:36
 */

namespace CalfManager\Controller;


use CalfManager\Model\Grupo;
use CalfManager\Utils\Validate\GrupoValidate;
use CalfManager\View\View;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Exception;

class GrupoController implements IController
{
    public function post(Request $request, Response $response): Response
    {
        try {
            $grupo = new Grupo();
            $data = json_decode($request->getBody()->getContents());
            $valida = (new GrupoValidate())->validatePost((array)$data);
            if ($valida) {
                $grupo->setNome($data->nome);
                $grupo->setDescricao($data->descricao);
                $grupo->cadastrar();
                return View::renderMessage($response, "success", "Grupo cadastrado com sucesso!", 201, "Sucesso ao cadastrar");
            } else {
                return View::renderMessage($response, "warning", $valida, 400);
            }
        }catch(Exception $e){
            return View::renderException($response, $e);
        }
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        try {
            $grupo = new Grupo();
            $page = (int)$request->getQueryParam('pagina');
            if ($request->getAttribute('id')) {
                $grupo->setId($request->getAttribute('id'));
            }
            if ($request->getAttribute('nome')) {
                $grupo->setNome($request->getQueryParam('nome'));
            }
            $search = $grupo->pesquisar($page);
            return View::render($response, $search);
        }catch(Exception $e){
            return View::renderException($response, $e);
        }
    }

    public function put(Request $request, Response $response): Response
    {
        try {
            $grupo = new Grupo();
            $data = json_decode($request->getBody()->getContents());
            $valida = (new GrupoValidate())->validatePost((array)$data);
            if ($valida) {
                $grupo->setId($request->getAttribute('id'));
                if(!is_null($data->nome)){
                    $grupo->setNome($data->nome);
                }
                if(!is_null($data->descricao)) {
                    $grupo->setDescricao($data->descricao);
                }
                if($grupo->alterar()) {
                    return View::renderMessage($response, "success", "Grupo cadastrado com sucesso!", 201, "Sucesso ao cadastrar");
                } else{
                    return View::renderMessage($response, "error", "Erro ao cadastrar Grupo", 500);
                }
            } else {
                return View::renderMessage($response, "warning", $valida, 400);
            }
        }catch(Exception $e){
            return View::renderException($response, $e);
        }
    }

    public function delete(Request $request, Response $response): Response
    {
        try {
            $grupo = new Grupo();
            if ($request->getAttribute('id')) {
                $grupo->setId($request->getAttribute('id'));
            }
            if ($grupo->deletar()) {
                return View::renderMessage($response, "success", "Grupo excluído com sucesso!", 201, "Sucesso ao excluir");
            }
        }catch (Exception $e){
            return View::renderMessage($response, $e);
        }
    }

}