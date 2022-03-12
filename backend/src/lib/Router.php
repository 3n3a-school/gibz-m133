<?php

/**
 * @author      Enea Krähenbühl <inquiry@3n3a.ch>
 * @copyright   Copyright (c), 2022 Enea Krähenbühl
 * @license     MIT public license
 */
namespace M133;

/**
 * PHP Router
 * Mountable routes, shorthands for GET, POST...
 * also has 404 callback
 */
class Router
{
    private $afterRoutes = array();

    private $beforeRoutes = array();

    protected $notFoundCallback = [];

    private $baseRoute = '';

    private $requestedMethod = '';

    private $serverBasePath;

    private $namespace = '';

    public function before($methods, $pattern, $function)
    {
        $pattern = $this->baseRoute . '/' . trim($pattern, '/');
        $pattern = $this->baseRoute ? rtrim($pattern, '/') : $pattern;

        foreach (explode('|', $methods) as $method) {
            $this->beforeRoutes[$method][] = array(
                'pattern' => $pattern,
                'function' => $function,
            );
        }
    }


    public function match($methods, $pattern, $function)
    {
        $pattern = $this->baseRoute . '/' . trim($pattern, '/');
        $pattern = $this->baseRoute ? rtrim($pattern, '/') : $pattern;

        foreach (explode('|', $methods) as $method) {
            $this->afterRoutes[$method][] = array(
                'pattern' => $pattern,
                'function' => $function,
            );
        }
    }


    public function all($pattern, $function)
    {
        $this->match('GET|POST|PUT|DELETE|OPTIONS|PATCH|HEAD', $pattern, $function);
    }


    public function get($pattern, $function)
    {
        $this->match('GET', $pattern, $function);
    }


    public function post($pattern, $function)
    {
        $this->match('POST', $pattern, $function);
    }


    public function patch($pattern, $function)
    {
        $this->match('PATCH', $pattern, $function);
    }


    public function delete($pattern, $function)
    {
        $this->match('DELETE', $pattern, $function);
    }


    public function put($pattern, $function)
    {
        $this->match('PUT', $pattern, $function);
    }


    public function options($pattern, $function)
    {
        $this->match('OPTIONS', $pattern, $function);
    }


    public function mount($baseRoute, $function)
    {
        $currentBaseRoute = $this->baseRoute;

        $this->baseRoute .= $baseRoute;

        call_user_func($function);

        $this->baseRoute = $currentBaseRoute;
    }


    public function getRequestHeaders()
    {
        $headers = array();

        if (function_exists('getallheaders')) {
            $headers = getallheaders();

                if ($headers !== false) {
                return $headers;
            }
        }

        foreach ($_SERVER as $name => $value) {
            if ((substr($name, 0, 5) == 'HTTP_') || ($name == 'CONTENT_TYPE') || ($name == 'CONTENT_LENGTH')) {
                $headers[str_replace(array(' ', 'Http'), array('-', 'HTTP'), ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }

        return $headers;
    }

    public function getRequestMethod()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
            ob_start();
            $method = 'GET';
        }

        elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $headers = $this->getRequestHeaders();
            if (isset($headers['X-HTTP-Method-Override']) && in_array($headers['X-HTTP-Method-Override'], array('PUT', 'DELETE', 'PATCH'))) {
                $method = $headers['X-HTTP-Method-Override'];
            }
        }

        return $method;
    }

    public function setNamespace($namespace)
    {
        if (is_string($namespace)) {
            $this->namespace = $namespace;
        }
    }


    public function getNamespace()
    {
        return $this->namespace;
    }

    public function run($callback = null)
    {
        $this->requestedMethod = $this->getRequestMethod();

        if (isset($this->beforeRoutes[$this->requestedMethod])) {
            $this->handle($this->beforeRoutes[$this->requestedMethod]);
        }

        $numberHandled = 0;
        if (isset($this->afterRoutes[$this->requestedMethod])) {
            $numberHandled = $this->handle($this->afterRoutes[$this->requestedMethod], true);
        }

        if ($numberHandled === 0) {
            $this->trigger404($this->afterRoutes[$this->requestedMethod]);
        }
        else {
            if ($callback && is_callable($callback)) {
                $callback();
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
            ob_end_clean();
        }

        return $numberHandled !== 0;
    }

    public function set404($match_function, $function = null)
    {
      if (!is_null($function)) {
        $this->notFoundCallback[$match_function] = $function;
      } else {
        $this->notFoundCallback['/'] = $match_function;
      }
    }

    public function trigger404($match = null){

        $numberHandled = 0;

        if (count($this->notFoundCallback) > 0)
        {
            foreach ($this->notFoundCallback as $route_pattern => $route_callable) {

              $matches = [];

              $is_match = $this->patternMatches($route_pattern, $this->getCurrentUri(), $matches, PREG_OFFSET_CAPTURE);

              if ($is_match) {

                $matches = array_slice($matches, 1);

                $params = array_map(function ($match, $index) use ($matches) {

                  if (isset($matches[$index + 1]) && isset($matches[$index + 1][0]) && is_array($matches[$index + 1][0])) {
                    if ($matches[$index + 1][0][1] > -1) {
                      return trim(substr($match[0][0], 0, $matches[$index + 1][0][1] - $match[0][1]), '/');
                    }
                  }

                  return isset($match[0][0]) && $match[0][1] != -1 ? trim($match[0][0], '/') : null;
                }, $matches, array_keys($matches));

                $this->invoke($route_callable);

                ++$numberHandled;
              }
            }
        }
        if (($numberHandled == 0) && (isset($this->notFoundCallback['/']))) {
            $this->invoke($this->notFoundCallback['/']);
        } elseif ($numberHandled == 0) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        }
    }

    private function patternMatches($pattern, $uri, &$matches, $flags)
    {
      $pattern = preg_replace('/\/{(.*?)}/', '/(.*?)', $pattern);

      return boolval(preg_match_all('#^' . $pattern . '$#', $uri, $matches, PREG_OFFSET_CAPTURE));
    }

    private function handle($routes, $quitAfterRun = false)
    {
        $numberHandled = 0;

        $uri = $this->getCurrentUri();

        foreach ($routes as $route) {

            $is_match = $this->patternMatches($route['pattern'], $uri, $matches, PREG_OFFSET_CAPTURE);

            if ($is_match) {

                $matches = array_slice($matches, 1);

                $params = array_map(function ($match, $index) use ($matches) {

                    if (isset($matches[$index + 1]) && isset($matches[$index + 1][0]) && is_array($matches[$index + 1][0])) {
                        if ($matches[$index + 1][0][1] > -1) {
                            return trim(substr($match[0][0], 0, $matches[$index + 1][0][1] - $match[0][1]), '/');
                        }
                    }

                    return isset($match[0][0]) && $match[0][1] != -1 ? trim($match[0][0], '/') : null;
                }, $matches, array_keys($matches));

                $this->invoke($route['function'], $params);

                ++$numberHandled;

                        if ($quitAfterRun) {
                    break;
                }
            }
        }

        return $numberHandled;
    }

    private function invoke($function, $params = array())
    {
        if (is_callable($function)) {
            call_user_func_array($function, $params);
        }

        elseif (stripos($function, '@') !== false) {
                list($controller, $method) = explode('@', $function);

                if ($this->getNamespace() !== '') {
                $controller = $this->getNamespace() . '\\' . $controller;
            }

            try {
                $reflectedMethod = new \ReflectionMethod($controller, $method);
                        if ($reflectedMethod->isPublic() && (!$reflectedMethod->isAbstract())) {
                    if ($reflectedMethod->isStatic()) {
                        forward_static_call_array(array($controller, $method), $params);
                    } else {
                                        if (\is_string($controller)) {
                            $controller = new $controller();
                        }
                        call_user_func_array(array($controller, $method), $params);
                    }
                }
            } catch (\ReflectionException $reflectionException) {
                    }
        }
    }


    public function getCurrentUri()
    {
        $uri = substr(rawurldecode($_SERVER['REQUEST_URI']), strlen($this->getBasePath()));

        if (strstr($uri, '?')) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }

        return '/' . trim($uri, '/');
    }


    public function getBasePath()
    {
        if ($this->serverBasePath === null) {
            $this->serverBasePath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
        }

        return $this->serverBasePath;
    }

    public function setBasePath($serverBasePath)
    {
        $this->serverBasePath = $serverBasePath;
    }
}