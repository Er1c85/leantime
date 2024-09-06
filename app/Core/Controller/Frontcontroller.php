<?php

namespace Leantime\Core\Controller;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Leantime\Core\Events\DispatchesEvents;
use Leantime\Core\Http\HtmxRequest;
use Leantime\Core\Http\IncomingRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Frontcontroller class
 *
 * @package    leantime
 * @subpackage core
 */
class Frontcontroller extends Router
{
    use DispatchesEvents;

    /**
     * @var string - last action that was fired
     */
    private string $lastAction = '';

    /**
     * @var string - fully parsed action
     */
    private string $fullAction = '';

    /**
     * @var IncomingRequest
     */
    private IncomingRequest $incomingRequest;

    /**
     * @var array - valid status codes
     */
    private array $validStatusCodes = array("100","101","200","201","202","203","204","205","206","300","301","302","303","304","305","306","307","400","401","402","403","404","405","406","407","408","409","410","411","412","413","414","415","416","417","500","501","502","503","504","505");

    /**
     * __construct - Set the rootpath of the server
     *
     * @param IncomingRequest $incomingRequest
     * @return void
     */
    public function __construct(IncomingRequest $incomingRequest)
    {
        $this->rootPath = ROOT;
        $this->incomingRequest = $incomingRequest;
        $this->currentRoute = $incomingRequest->getCurrentRoute();
    }

    /**
     * run - executes the action depending on Request or firstAction
     *
     * @access public
     * @param string $action
     * @param int    $httpResponseCode
     * @return Response
     * @throws BindingResolutionException
     */
    public function dispatch(string $action = '', int $httpResponseCode = 200): Response
    {
        $this->fullAction = empty($action) ? $this->currentRoute : $action;

        if ($this->fullAction == '') {
            return $this->dispatch("errors.error404", 404);
        }

        //execute action
        return $this->executeAction($this->fullAction, array());
    }

    /**
     * executeAction - includes the class in includes/modules by the Request
     *
     * @access private
     * @param string $completeName actionname.filename
     * @param array  $params
     * @return Response
     * @throws BindingResolutionException
     */
    private function executeAction(string $completeName, array $params = array()): Response
    {
        $namespace = "Leantime\\";
        $actionName = Str::studly(self::getActionName($completeName));
        $moduleName = Str::studly(self::getModuleName($completeName));

        $this->dispatch_event("execute_action_start", ["action" => $actionName, "module" => $moduleName]);

        $controllerNs = "Domain";

        $controllerType = 'Controllers';
        if (
            ($this->incomingRequest instanceof HtmxRequest) &&
            $this->incomingRequest->header("is-modal") == false &&
            $this->incomingRequest->header("hx-boosted") == false
        ) {
            $controllerType = 'Hxcontrollers';
        }

        $classname = $namespace . "" . $controllerNs . "\\" . $moduleName . "\\" . $controllerType . "\\" . $actionName;

        if (! class_exists($classname)) {
             $classname = $namespace . "Plugins\\" . $moduleName . "\\" . $controllerType . "\\" . $actionName;

            $enabledPlugins = app()->make(\Leantime\Domain\Plugins\Services\Plugins::class)->getEnabledPlugins();

            $pluginEnabled = false;
            foreach ($enabledPlugins as $key => $obj) {
                if (strtolower($obj->foldername) !== strtolower($moduleName)) {
                    continue;
                }
                $pluginEnabled = true;
                break;
            }

            if (! $pluginEnabled || ! class_exists($classname)) {
                return $controllerType == 'Hxcontrollers' ? new Response('', 404) : $this->redirect(BASE_URL . "/errors/error404", 307);
            }
        }

        //Setting default response code to 200, can be changed in controller
        $this->setResponseCode(200);

        $this->lastAction = $completeName;

        $this->dispatch_event("execute_action_end", ["action" => $actionName, "module" => $moduleName]);

        return app()->make($classname)->getResponse();
    }

    /**
     * getActionName - split string to get actionName
     *
     * @access public
     * @param string|null $completeName
     * @return string
     * @throws BindingResolutionException
     */
    public static function getActionName(string $completeName = null): string
    {
        $completeName ??= currentRoute();
        $actionParts = explode(".", empty($completeName) ? currentRoute() : $completeName);

        //If not action name was given, call index controller
        if (is_array($actionParts) && count($actionParts) == 1) {
            return "index";
        } elseif (is_array($actionParts) && count($actionParts) == 2) {
            return $actionParts[1];
        }

        return "";
    }

    /**
     * getModuleName - split string to get modulename
     *
     * @access public
     * @param string|null $completeName
     * @return string
     * @throws BindingResolutionException
     */
    public static function getModuleName(string $completeName = null): string
    {
        $completeName ??= currentRoute();
        $actionParts = explode(".", empty($completeName) ? currentRoute() : $completeName);

        if (is_array($actionParts)) {
            return $actionParts[0];
        }

        return "";
    }

    /**
     * redirect - redirects to a given url
     *
     * @param string $url
     * @param int    $http_response_code
     * @return RedirectResponse
     */
    public static function redirect(string $url, int $http_response_code = 303): RedirectResponse
    {
        return new RedirectResponse(
            trim(preg_replace('/\s\s+/', '', strip_tags($url))),
            $http_response_code
        );
    }

    /**
     * getCurrentRoute - gets current route
     * @deprecated use request class to get current route
     *
     * @return string
     */
    public static function getCurrentRoute()
    {
        return app('request')->getCurrentRoute();
    }

    /**
     * setResponseCode - sets the response code
     *
     * @param int $responseCode
     * @return void
     */
    public function setResponseCode(int $responseCode): void
    {
        http_response_code($responseCode);
    }

    protected function getControllerMethod()
    {

        //Get http methods
        $method = $this->incomingRequest;

        //HEAD execution is equal to GET. Server can handle the content response cutting for us.
        if (strtoupper($method) == "HEAD") {
            $method = "GET";
        }

        $available_params = [
            'controller' => $this,
            'method' => $method,
            'params' => $params,
        ];

        self::dispatch_event('before_init', $available_params);
        if (method_exists($this, 'init')) {
            app()->call([$this, 'init']);
        }

        self::dispatch_event('before_action', $available_params);

        if (method_exists($this, $method)) {
            $this->response = $this->$method($params);
        } elseif (method_exists($this, 'run')) {
            $this->response = $this->run();
        } else {
            Log::error('Method not found: ' . $method);
            Frontcontroller::redirect(BASE_URL . "/errors/error501", 307);
        }
    }

    protected function findRoute($request) {


        $this->current = $route = $this->routes->match($request);

        //$route->setContainer($this->container);


        $namespace = "Leantime\\";
        $actionName = Str::studly(self::getActionName($completeName));
        $moduleName = Str::studly(self::getModuleName($completeName));

        $this->dispatch_event("execute_action_start", ["action" => $actionName, "module" => $moduleName]);

        $controllerNs = "Domain";

        $controllerType = 'Controllers';
        if (
            ($this->incomingRequest instanceof HtmxRequest) &&
            $this->incomingRequest->header("is-modal") == false &&
            $this->incomingRequest->header("hx-boosted") == false
        ) {
            $controllerType = 'Hxcontrollers';
        }

        $classname = $namespace . "" . $controllerNs . "\\" . $moduleName . "\\" . $controllerType . "\\" . $actionName;

        if (! class_exists($classname)) {
            $classname = $namespace . "Plugins\\" . $moduleName . "\\" . $controllerType . "\\" . $actionName;

            $enabledPlugins = app()->make(\Leantime\Domain\Plugins\Services\Plugins::class)->getEnabledPlugins();

            $pluginEnabled = false;
            foreach ($enabledPlugins as $key => $obj) {
                if (strtolower($obj->foldername) !== strtolower($moduleName)) {
                    continue;
                }
                $pluginEnabled = true;
                break;
            }

            if (! $pluginEnabled || ! class_exists($classname)) {
                return $controllerType == 'Hxcontrollers' ? new Response('', 404) : $this->redirect(BASE_URL . "/errors/error404", 307);
            }
        }
    }
}
