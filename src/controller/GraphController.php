<?php
/**
 * Created by PhpStorm.
 * User: marci
 * Date: 09/03/2018
 * Time: 12:27
 */

namespace src\controller;


use Exception;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use src\view\View;

class GraphController implements IController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function post(Request $request, Response $response): Response
    {
        // TODO: Implement post() method.
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function get(Request $request, Response $response, array $args): Response
    {
        try {
            $classToInstance = '\\src\\model\\' . ucfirst($request->getQueryParam('module'));
            $instance = new $classToInstance;
            $toArray = explode("-", $request->getQueryParam('chart'));
            $methodCamelCase = "graph";
            for ($i = 0; $i < count($toArray); $i++) {
                $methodCamelCase .= ucfirst($toArray[$i]);
            }
            return View::render($response, $instance->graph($methodCamelCase, $request->getQueryParams()));
        } catch (Exception $e) {
            return View::renderException($response, $e);
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function put(Request $request, Response $response): Response
    {
        // TODO: Implement put() method.
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function delete(Request $request, Response $response): Response
    {
        // TODO: Implement delete() method.
    }
}