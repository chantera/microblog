<?php

function bootstrap() {
    $script = $_SERVER['SCRIPT_NAME'];
    define('ENTRY_POINT', '/index.php');
    define('WEBROOT', substr($script, 0, strrpos($script, ENTRY_POINT)));
    session_start();
    return true;
}

class App
{
    protected $_routes = array(
        'GET'    => array(),
        'POST'   => array(),
        'PUT'    => array(),
        'DELETE' => array(),
        'ERROR'  => array(),
    );

    public function __construct()
    {
    }

    public function run()
    {
        $dispatcher = new Dispatcher();
        return $dispatcher->dispatch(new Request(), $this->_routes);
    }

    public function get($url, $callback)
    {
        $this->_addRoute('GET', $url, $callback);
    }

    public function post($url, $callback)
    {
        $this->_addRoute('POST', $url, $callback);
    }

    public function put($url, $callback)
    {
        $this->_addRoute('PUT', $url, $callback);
    }

    public function delete($url, $callback)
    {
        $this->_addRoute('DELETE', $url, $callback);
    }

    public function error($code, $callback)
    {
        $this->_addRoute('ERROR', (string) $code, $callback);
    }

    protected function _addRoute($method, $url, $callback)
    {
        if ( !is_callable($callback) && !is_string($callback) ) {
            throw new InvalidArgumentException('invalid callback type');
        }
        $this->_routes[$method][$url] = $callback;
    }
}

class Dispatcher
{
    public function dispatch(Request $request, $routes)
    {
        if ( isset($routes[$request->method][$request->path]) ) {
            $callback = $routes[$request->method][$request->path];
        } else {
            $callback = $routes['ERROR']['404'];
        }
        if ( is_callable($callback) ) {
            $func = $callback;
            $args = array($request);
        } else if ( is_string($callback) ) {
            $names = explode(':', $callback);
            if ( count($names) == 2 ) {
                $class = $names[0] . 'Controller';
                $func = array(new $class($request), $names[1]);
                $args = array();
            } else {
                $func = $names[0];
                $args = array($request);
            }
        } else {
            throw new Exception('cannot resolve callback');
        }
        call_user_func_array($func, $args);
        return true;
    }
}

class Request
{
    protected $_values;

    public function __construct()
    {
        $s = $_SERVER;
        $path = array_key_exists('PATH_INFO', $s) ? $s['PATH_INFO'] : '/';
        $this->_values = array(
            'method' => $s['REQUEST_METHOD'],
            'uri'    => $s['REQUEST_URI'],
            'path'   => $path,
            'get'    => $_GET,
            'post'   => $_POST,
        );
    }

    public function __get($name)
    {
        return $this->_values[$name];
    }
}

class Controller
{
    protected $_request;
    protected $_vars = array();

    public function __construct(Request $request)
    {
        $this->_request = $request;
    }

    protected function _set(array $vars)
    {
        $this->_vars = $vars;
    }

    protected function _render($template)
    {
        extract($this->_vars, EXTR_OVERWRITE);
        include_once($template);
    }
}

bootstrap();
