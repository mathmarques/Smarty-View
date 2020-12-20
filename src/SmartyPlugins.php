<?php
namespace Slim\Views;

use Psr\Http\Message\UriInterface;
use Slim\Interfaces\RouteParserInterface;
use Smarty_Internal_Template;

class SmartyPlugins
{
    /**
     * @var RouteParserInterface
     */
    protected $routeParser;

    /**
     * @var string
     */
    protected $basePath = '';

    /**
     * @var UriInterface
     */
    protected $uri;

    public function __construct(RouteParserInterface $routeParser, UriInterface $uri, string $basePath = '')
    {
        $this->routeParser = $routeParser;
        $this->uri = $uri;
        $this->basePath = $basePath;
    }

    /**
     * Get the url for a named route
     *
     * @param array $params
     * @param Smarty_Internal_Template $template
     * @return string
     */
    public function urlFor(array $params, Smarty_Internal_Template $template): string
    {
        return $this->routeParser->urlFor($params['name'], $params['data'] ?? [], $params['queryParams'] ?? []);
    }

    /**
     * Get the full url for a named route
     *
     * @param array $params
     * @param Smarty_Internal_Template $template
     * @return string
     */
    public function fullUrlFor(array $params, Smarty_Internal_Template $template): string
    {
        return $this->routeParser->fullUrlFor($this->uri, $params['name'], $params['data'] ?? [], $params['queryParams'] ?? []);
    }

    /**
     * @param array $params
     * @param Smarty_Internal_Template $template
     * @return string
     */
    public function isCurrentUrl(array $params, Smarty_Internal_Template $template): string
    {
        $currentUrl = $this->basePath.$this->uri->getPath();
        $result = $this->routeParser->urlFor($params['name'], $params['data'] ?? []);

        return $result === $currentUrl;
    }

    /**
     * Get current path on given Uri
     *
     * @param array $params
     * @param Smarty_Internal_Template $template
     * @return string
     */
    public function getCurrentUrl(array $params, Smarty_Internal_Template $template): string
    {
        $currentUrl = $this->basePath.$this->uri->getPath();
        $query = $this->uri->getQuery();

        if (($params['withQueryString'] ?? false) && !empty($query)) {
            $currentUrl .= '?'.$query;
        }

        return $currentUrl;
    }

    /**
     * Get the uri
     *
     * @return UriInterface
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * Set the uri
     *
     * @param UriInterface $uri
     *
     * @return self
     */
    public function setUri(UriInterface $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Get the base path
     *
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * Set the base path
     *
     * @param string $basePath
     *
     * @return self
     */
    public function setBasePath(string $basePath): self
    {
        $this->basePath = $basePath;

        return $this;
    }
}
