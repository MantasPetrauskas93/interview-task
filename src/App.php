<?php

declare(strict_types=1);

namespace WHInterviewTask;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class App
{
    public function run()
    {
        $routes = new RouteCollection();

        $routes->add(
            'customer_list',
            new Route('/api/customers', [
                '_controller' => [Customer::class, 'customers_list'],
            ],
            methods: ['GET']
            )
        );

        $routes->add(
            'customers',
            new Route('/api/customers', [
                '_controller' => [Customer::class, 'create'],
            ],
                methods: ['POST']
            )
        );


        $routes->add(
            'order',
            new Route('/api/order/details', [
                '_controller' => [Order::class, 'get_order_details'],
            ],
                methods: ['POST']
            )
        );

        $routes->add(
            'order_create',
            new Route('/api/order', [
                '_controller' => [Order::class, 'create_order'],
            ],
                methods: ['POST']
            )
        );

        $routes->add(
            'calculate_order_total',
            new Route('/api/order/total', [
                '_controller' => [Order::class, 'calculate_order_total'],
            ],
                methods: ['POST']
            )
        );


        $request = Request::createFromGlobals();

        $requestContext = new RequestContext();
        $requestContext->fromRequest($request);

        $matcher = new UrlMatcher($routes, $requestContext);

        try {
            $parameters = $matcher->match($request->getPathInfo());
            $controller = $parameters['_controller'];
        } catch (\Exception $e) {
            throw new \Exception('Route not found');
        }

        $class = $controller[0];
        $method = $controller[1];

        $object = new $class();
        $this->sendResponse(200, $object->$method($request->request));
    }

    public static function config($key = null, $default = null)
    {
        $config = [
            'mysql' => [
                'host' => getenv('MYSQL_HOST'),
                'db' => getenv('MYSQL_DATABASE'),
                'user' => getenv('MYSQL_USER'),
                'password' => getenv('MYSQL_PASSWORD'),
            ]
        ];

        $keys = explode('.', $key);
        foreach ($keys as $k) {
            if (!isset($config[$k])) {
                return null;
            }

            $config = $config[$k];
        }

        return $config;
    }

    function sendResponse($statusCode, $message) {
        http_response_code($statusCode);

        $result = json_encode($message);
        echo $result;
    }
}
