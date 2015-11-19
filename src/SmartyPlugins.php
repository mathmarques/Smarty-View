<?php
namespace Slim\Views;

use Slim\Interfaces\RouterInterface;
use Smarty_Internal_Template;

class SmartyPlugins
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var string|\Slim\Http\Uri
     */
    private $uri;

    public function __construct(RouterInterface $router, $uri)
    {
        $this->router = $router;
        $this->uri = $uri;
    }

    public function pathFor($params, Smarty_Internal_Template $template)
    {
        if (!isset($params['data'])) {
            $params['data'] = [];
        }

        if (!isset($params['queryParams'])) {
            $params['queryParams'] = [];
        }

        return $this->router->pathFor($params['name'], $params['data'], $params['queryParams']);
    }

    public function baseUrl($params, Smarty_Internal_Template $template)
    {
        if (is_string($this->uri)) {
            return $this->uri;
        }
        if (method_exists($this->uri, 'getBaseUrl')) {
            return $this->uri->getBaseUrl();
        }
    }
}
