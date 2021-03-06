<?php /** @noinspection PhpUnusedParameterInspection */

/** @noinspection PhpUnusedParameterInspection */

namespace CalfManager\Utils;

use Exception;
use Slim\App;
use Slim\Exception\MethodNotAllowedException;
use Slim\Exception\NotFoundException;
use CalfManager\Controller\IController;
use CalfManager\View\View;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Api
{
    private $app;

    /**
     * Api constructor.
     */
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json;charset=UTF-8');
        header('Access-Control-Allow-Headers: *');
        header("Content-Disposition: inline; filename=CalfAPI;");
        header(
            'Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS'
        );
        $c = Config::SLIM_CONTAINER;
        $c['notFoundHandler'] = function ($c) {
            return function (Request $request, Response $response) use ($c) {
                $response->withStatus(404);
                return View::renderMessage(
                    $response,
                    'error',
                    'Página não encontrada',
                    404
                );
            };
        };

        $c['errorHandler'] = function ($c) {
            return function (
                Request $request,
                Response $response,
                Exception $error
            ) use ($c) {
                $response->withStatus(500);
                return View::renderException($response, $error);
            };
        };
        $this->app = new App($c);

        //        $this->app->add(function ($req, $res, $next) {
        //            $response = $next($req, $res);
        //            return $response
        //                ->withHeader('Access-Control-Allow-Origin', '*')
        //                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        //                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
        //        });
        //Carregar o timezone da aplicação.
        Config::loadTimezone();
    }

    public function getDatabase()
    {
        new Database();
    }

    /**
     * return IController
     */
    public function groupRoutes()
    {
        $this->groupAuth();
        $this->groupModules();
    }

    public function groupModules()
    {
        $app = $this->app;

        $app->group('/{classname}', function () use ($app) {
            $app->get('', function (
                Request $request,
                Response $response,
                array $args
            ) {
                try {
                    $class = Api::invokeClass(
                        $args['classname'],
                        $request,
                        $response
                    );
                    return $class->get($request, $response, $args);
                } catch (Exception $e) {
                    return View::renderException($response, $e);
                }
            });

            $app->get('/{id:[0-9]+}', function (
                Request $request,
                Response $response,
                array $args
            ) {
                $class = Api::invokeClass(
                    $args['classname'],
                    $request,
                    $response
                );
                return $class->get($request, $response, $args);
            });

            $app->post('', function (
                Request $request,
                Response $response,
                array $args
            ) {
                $class = Api::invokeClass(
                    $args['classname'],
                    $request,
                    $response
                );
                return $class->post($request, $response);
            });

            $app->put('/{id:[0-9]+}', function (
                Request $request,
                Response $response,
                array $args
            ) {
                $class = Api::invokeClass(
                    $args['classname'],
                    $request,
                    $response
                );
                return $class->put($request, $response);
            });
            $app->delete('/{id:[0-9]+}', function (
                Request $request,
                Response $response,
                array $args
            ) {
                $class = Api::invokeClass(
                    $args['classname'],
                    $request,
                    $response
                );
                return $class->delete($request, $response);
            });
        });
    }

    public function groupAuth()
    {
        $app = $this->app;

        $app->group('/auth', function () use ($app) {
            $app->post('/in', function (
                Request $request,
                Response $response,
                array $args
            ) {
                //                $class = Api::invokeClass($args['classname'], $request, $response);
                //                return $class->post($request, $response, $args);
            });
            $app->delete('/out', function (
                Request $request,
                Response $response,
                array $args
            ) {
                //                $class = Api::invokeClass($args['classname'], $request, $response);
                //                return $class->delete($request, $response);
            });
        });
    }

    /**
     * @param String $classname
     * @param Request $request
     * @param Response $response
     * @return IController | Response
     * @throws Exception
     */
    public static function invokeClass(
        string $classname,
        Request $request,
        Response $response
    )
    {
        $class =
            "\\CalfManager\\Controller\\" . ucfirst($classname) . "Controller";
        if (!class_exists($class)) {
            throw new Exception("Módulo não cadastrado na API");
        }
        return new $class($request, $response);
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function runApp()
    {
        try {
            return $this->app->run();
        } catch (MethodNotAllowedException $e) {

        } catch (NotFoundException $e) {

        } catch (Exception $e) {

        }
    }
}
