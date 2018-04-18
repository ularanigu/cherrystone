<?php
declare(strict_types=1);
/**
 * Cherrystone Authentication.
 * A more secure web.
 *
 * @license <https://github.com/ularanigu/cherrystone/blob/master/LICENSE>.
 * @link    <https://github.com/ularanigu/cherrystone>.
 */

use PHPUnit\Framework\TestCase;

use Ularanigu\Cherrystone\Service\BrowserBasedService;

class TestBrowserBasedService extends TestCase
{
    public function testConstructor()
    {
        $service1 = new BrowserBasedService([], 'my-alias');
        $this->assertEquals($service1->alias, 'my-alias');
      
    }
}
