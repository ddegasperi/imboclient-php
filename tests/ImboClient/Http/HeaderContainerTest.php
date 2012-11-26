<?php
/**
 * ImboClient
 *
 * Copyright (c) 2011-2012, Christer Edvartsen <cogo@starzinger.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * * The above copyright notice and this permission notice shall be included in
 *   all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @package ImboClient\TestSuite
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011-2012, Christer Edvartsen <cogo@starzinger.net>
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/imbo/imboclient-php
 */

namespace ImboClient\Http;

/**
 * @package ImboClient\TestSuite
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011-2012, Christer Edvartsen <cogo@starzinger.net>
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/imbo/imboclient-php
 */
class HeaderContainerTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var HeaderContainer
     */
    private $container;

    /**
     * Set up the header container instance
     */
    public function setUp() {
        $this->container = new HeaderContainer();
    }

    /**
     * Tear down the header container instance
     */
    public function tearDown() {
        $this->container = null;
    }

    /**
     * Return different keys and values
     *
     * @return array[]
     */
    public function getKeysAndValues() {
        return array(
            array('key', 'key', 'value'),
            array('KEY', 'key', 'value'),
            array('Some_key', 'some-key', 'value'),
            array('SOME-KEY', 'some-key', 'value'),
        );
    }

    /**
     * The container must be able to set and get parameters as well as normalize the key names
     *
     * @dataProvider getKeysAndValues
     * @covers ImboClient\Http\HeaderContainer::set
     * @covers ImboClient\Http\HeaderContainer::get
     * @covers ImboClient\Http\HeaderContainer::getName
     */
    public function testCanSetAndGetValues($key, $internalKey, $value) {
        $this->assertSame($this->container, $this->container->set($key, $value));
        $this->assertSame($value, $this->container->get($key));
        $this->assertSame($value, $this->container->get($internalKey));
    }

    /**
     * The container must be able to check for existing keys
     *
     * @dataProvider getKeysAndValues
     * @covers ImboClient\Http\HeaderContainer::set
     * @covers ImboClient\Http\HeaderContainer::has
     * @covers ImboClient\Http\HeaderContainer::getName
     */
    public function testCanCheckForExistingKeys($key, $internalKey, $value) {
        $this->assertFalse($this->container->has($key));
        $this->assertFalse($this->container->has($internalKey));
        $this->assertSame($this->container, $this->container->set($key, $value));
        $this->assertTrue($this->container->has($key));
        $this->assertTrue($this->container->has($internalKey));
    }

    /**
     * The container must be able to remove keys
     *
     * @dataProvider getKeysAndValues
     * @covers ImboClient\Http\HeaderContainer::set
     * @covers ImboClient\Http\HeaderContainer::has
     * @covers ImboClient\Http\HeaderContainer::remove
     * @covers ImboClient\Http\HeaderContainer::getName
     */
    public function testCanRemoveKeys($key, $internalKey, $value) {
        $this->assertSame($this->container, $this->container->set($key, $value));
        $this->assertTrue($this->container->has($key));
        $this->assertTrue($this->container->has($internalKey));
        $this->assertSame($this->container, $this->container->remove($key));
        $this->assertFalse($this->container->has($key));
        $this->assertFalse($this->container->has($internalKey));
    }

    /**
     * The container must be able to return all keys
     *
     * @covers ImboClient\Http\HeaderContainer::__construct
     * @covers ImboClient\Http\HeaderContainer::getAll
     * @covers ImboClient\Http\HeaderContainer::getName
     */
    public function testCanReturnAllKeys() {
        $parameters = array(
            'key' => 'value',
            'otherKey' => 'otherValue',
            'content-length' => 123,
            'CONTENT_LENGTH' => 234,
            'content-type' => 'text/html',
            'CONTENT_TYPE' => 'image/png',
            'IF_NONE_MATCH' => 'asd',
        );

        $container = new HeaderContainer($parameters);

        $this->assertSame(array(
            'key' => 'value',
            'otherkey' => 'otherValue',
            'content-length' => 234,
            'content-type' => 'image/png',
            'if-none-match' => 'asd',
        ), $container->getAll());
    }
}
