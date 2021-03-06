<?php
/*
 * FluentDOM
 *
 * @link https://thomas.weinert.info/FluentDOM/
 * @copyright Copyright 2009-2021 FluentDOM Contributors
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */

namespace FluentDOM\Query\Manipulation {

  use FluentDOM\TestCase;

  require_once __DIR__.'/../../TestCase.php';

  class UnwrapTest extends TestCase {

    protected $_directory = __DIR__;
    /**
     * @group Manipulation
     * @group ManipulationAround
     * @covers \FluentDOM\Query
     */
    public function testUnwrap(): void {
      $fd = $this->getQueryFixtureFromFunctionName(__FUNCTION__);
      $fd
        ->find('//p')
        ->unwrap('self::div');
      $this->assertFluentDOMQueryEqualsXMLFile(__FUNCTION__, $fd);
    }

    /**
     * @group Manipulation
     * @group ManipulationAround
     * @covers \FluentDOM\Query
     */
    public function testUnwrapWithoutSelector(): void {
      $fd = $this->getQueryFixtureFromString('<section><div><p>One</p></div><div><p>Two</p></div></section>');
      $fd
        ->find('//p')
        ->unwrap();
      $this->assertXmlStringEqualsXmlString(
        '<section><p>One</p><p>Two</p></section>',
        (string)$fd->formatOutput()
      );
    }

    /**
     * @group Manipulation
     * @group ManipulationAround
     * @covers \FluentDOM\Query
     */
    public function testUnwrapWithSelector(): void {
      $fd = $this->getQueryFixtureFromString(
        '<section><div class="one"><p>One</p></div><div class="two"><p>Two</p></div></section>'
      );
      $fd
        ->find('//p')
        ->unwrap('self::*[@class = "two"]');
      $this->assertXmlStringEqualsXmlString(
        '<section><div class="one"><p>One</p></div><p>Two</p></section>',
        (string)$fd->formatOutput()
      );
    }
  }
}
