# Slim Framework 3 Smarty View

[![Build Status](https://travis-ci.org/mathmarques/Smarty-View.svg)](https://travis-ci.org/mathmarques/Smarty-View) [![Latest Stable Version](https://poser.pugx.org/mathmarques/smarty-view/v/stable)](https://packagist.org/packages/mathmarques/smarty-view) [![Total Downloads](https://poser.pugx.org/mathmarques/smarty-view/downloads)](https://packagist.org/packages/mathmarques/smarty-view) [![License](https://poser.pugx.org/mathmarques/smarty-view/license)](https://packagist.org/packages/mathmarques/smarty-view)

This is a Slim Framework 3 view helper built on top of the Smarty templating component. You can use this component to create and render templates in your Slim Framework application.

## Install

Via [Composer](https://getcomposer.org/)

```bash
$ composer require mathmarques/smarty-view
```

Requires Slim Framework 3 and PHP 5.5.0 or newer.

## Usage

```php
// Create Slim app
$app = new \Slim\App();

// Fetch DI Container
$container = $app->getContainer();

// Register Smarty View helper
$container['view'] = function ($c) {
    $view = new \Slim\Views\Smarty('path/to/templates', [
        'cacheDir' => 'path/to/cache',
        'compileDir' => 'path/to/compile',
        'pluginsDir' => ['path/to/plugins', 'another/path/to/plugins']
    ]);
    
    // Add Slim specific plugins
    $smartyPlugins = new \Slim\Views\SmartyPlugins($c['router'], $c['request']->getUri());
    $view->registerPlugin('function', 'path_for', [$smartyPlugins, 'pathFor']);
    $view->registerPlugin('function', 'base_url', [$smartyPlugins, 'baseUrl']);

    return $view;
};

// Define named route
$app->get('/hello/{name}', function ($request, $response, $args) {
    return $this->view->render($response, 'profile.tpl', [
        'name' => $args['name']
    ]);
})->setName('profile');

// Run app
$app->run();
```

## Custom template functions

This component exposes a custom `path_for()` and `base_url()` functions to your Smarty templates.

You can use `path_for()` function to generate complete URLs to any Slim application named route. This is an example Smarty template:

    {extends 'layout.tpl'}

    {block name=body}
    <h1>User List</h1>
    <ul>
        <li><a href="{path_for name="profile" data=["name" => "Matheus"]}">Matheus</a></li>
    </ul>
    {/block}

## Testing

```bash
phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Matheus Marques](https://github.com/mathmarques)
- Project based on [Twig-View](https://github.com/slimphp/Twig-View) by [Josh Lockhart](https://github.com/codeguy)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
