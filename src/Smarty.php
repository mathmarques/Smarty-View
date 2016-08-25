<?php
namespace Slim\Views;

use Psr\Http\Message\ResponseInterface;
use Slim\Interfaces\RouterInterface;
use SmartyException;

/**
 * Smarty View
 *
 * This class is a Slim Framework 3 view helper built
 * on top of the Smarty templating component. Smarty is
 * a PHP component created by New Digital Group, Inc.
 *
 * @link http://www.smarty.net/
 */
class Smarty implements \ArrayAccess
{
    /**
     * Smarty instance
     *
     * @var \Smarty
     */
    protected $smarty;

    /**
     * Default view variables
     *
     * @var array
     */
    protected $defaultVariables = [];

    /********************************************************************************
     * Constructors and service provider registration
     *******************************************************************************/

    /**
     * Create new Smarty view
     *
     * @param string|array $paths Paths to templates directories
     * @param array $settings Smarty settings
     */
    public function __construct($paths, $settings = [])
    {
        $this->smarty = new \Smarty();

        $this->smarty->setTemplateDir($paths);

        if (isset($settings['cacheDir'])) {
            $this->smarty->setCacheDir($settings['cacheDir']);
        }

        if (isset($settings['compileDir'])) {
            $this->smarty->setCompileDir($settings['compileDir']);
        }

        if (isset($settings['pluginsDir'])) {
            $this->smarty->addPluginsDir($settings['pluginsDir']);
        }
    }

    /********************************************************************************
     * Methods
     *******************************************************************************/

    /**
     * Method to add the Slim plugins to Smarty
     *
     * @param RouterInterface $router
     * @param string|\Slim\Http\Uri $uri
     *
     * @deprecated Deprecated since version 1.1.0. Use registerPlugin instead. See README for more info.
     */
    public function addSlimPlugins(RouterInterface $router, $uri)
    {
        $smartyPlugins = new SmartyPlugins($router, $uri);
        $this->smarty->registerPlugin('function', 'path_for', [$smartyPlugins, 'pathFor']);
        $this->smarty->registerPlugin('function', 'base_url', [$smartyPlugins, 'baseUrl']);
    }

    /**
     * Proxy method to register a plugin to Smarty
     *
     * @param  string $type plugin type
     * @param  string $tag name of template tag
     * @param  callback $callback PHP callback to register
     * @param  boolean $cacheable if true (default) this function is cachable
     * @param  array $cache_attr caching attributes if any
     *
     * @return self
     *
     * @throws SmartyException when the plugin tag is invalid
     */
    public function registerPlugin($type, $tag, $callback, $cacheable = true, $cache_attr = null)
    {
        $this->smarty->registerPlugin($type, $tag, $callback, $cacheable, $cache_attr);
    }

    /**
     * Fetch rendered template
     *
     * @param  string $template Template pathname relative to templates directory
     * @param  array $data Associative array of template variables
     *
     * @return string
     */
    public function fetch($template, $data = [])
    {
        $data = array_merge($this->defaultVariables, $data);

        $this->smarty->assign($data);

        return $this->smarty->fetch($template);
    }

    /**
     * Output rendered template
     *
     * @param ResponseInterface $response
     * @param  string $template Template pathname relative to templates directory
     * @param  array $data Associative array of template variables
     * @return ResponseInterface
     */
    public function render(ResponseInterface $response, $template, $data = [])
    {
        $response->getBody()->write($this->fetch($template, $data));

        return $response;
    }

    /********************************************************************************
     * Accessors
     *******************************************************************************/

    /**
     * Return Smarty instance
     *
     * @return \Smarty
     */
    public function getSmarty()
    {
        return $this->smarty;
    }

    /********************************************************************************
     * ArrayAccess interface
     *******************************************************************************/

    /**
     * Does this collection have a given key?
     *
     * @param  string $key The data key
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->defaultVariables);
    }

    /**
     * Get collection item for key
     *
     * @param string $key The data key
     *
     * @return mixed The key's value, or the default value
     */
    public function offsetGet($key)
    {
        return $this->defaultVariables[$key];
    }

    /**
     * Set collection item
     *
     * @param string $key The data key
     * @param mixed $value The data value
     */
    public function offsetSet($key, $value)
    {
        $this->defaultVariables[$key] = $value;
    }

    /**
     * Remove item from collection
     *
     * @param string $key The data key
     */
    public function offsetUnset($key)
    {
        unset($this->defaultVariables[$key]);
    }

    /********************************************************************************
     * Countable interface
     *******************************************************************************/

    /**
     * Get number of items in collection
     *
     * @return int
     */
    public function count()
    {
        return count($this->defaultVariables);
    }

    /********************************************************************************
     * IteratorAggregate interface
     *******************************************************************************/

    /**
     * Get collection iterator
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->defaultVariables);
    }
}
