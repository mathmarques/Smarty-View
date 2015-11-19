<?php
namespace Slim\Tests\Views;

use Slim\Views\Smarty;

require dirname(__DIR__) . '/vendor/autoload.php';

class SmartyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Smarty
     */
    protected $view;

    public function setUp()
    {
        $this->view = new Smarty(dirname(__FILE__) . '/templates');
    }

    public function testFetch()
    {
        $output = $this->view->fetch('hello.tpl', [
            'name' => 'Matheus'
        ]);

        $this->assertEquals("<p>Hello, my name is Matheus.</p>\n", $output);
    }

    public function testRender()
    {
        $mockBody = $this->getMockBuilder('Psr\Http\Message\StreamInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mockResponse = $this->getMockBuilder('Psr\Http\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mockBody->expects($this->once())
            ->method('write')
            ->with("<p>Hello, my name is Matheus.</p>\n")
            ->willReturn(34);

        $mockResponse->expects($this->once())
            ->method('getBody')
            ->willReturn($mockBody);

        $response = $this->view->render($mockResponse, 'hello.tpl', [
            'name' => 'Matheus'
        ]);
        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
    }
}
