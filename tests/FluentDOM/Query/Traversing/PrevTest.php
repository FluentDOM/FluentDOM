<?php
/*
 * FluentDOM
 *
 * @link https://thomas.weinert.info/FluentDOM/
 * @copyright Copyright 2009-2021 FluentDOM Contributors
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */

namespace FluentDOM\Query\Traversing {

  use FluentDOM\TestCase;

  require_once __DIR__.'/../../TestCase.php';

  class PrevTest extends TestCase {

    protected $_directory = __DIR__;

    /**
     * @group Traversing
     * @group TraversingFind
     * @covers \FluentDOM\Query::prev
     */
    public function testPrev(): void {
      $fd = $this->getQueryFixtureFromFunctionName(__FUNCTION__);
      $fd ->find('//div[@id = "start"]')
        ->prev()
        ->addClass('before');
      $this->assertFluentDOMQueryEqualsXMLFile(__FUNCTION__, $fd);
    }

    /**
     * @group Traversing
     * @group TraversingFind
     * @covers \FluentDOM\Query::prev
     */
    public function testPrevExpression(): void {
      $fd = $this->getQueryFixtureFromFunctionName(__FUNCTION__);
      $fd
        ->find('//div[@class = "here"]')
        ->prev()
        ->addClass('nextTest');
      $this->assertFluentDOMQueryEqualsXMLFile(__FUNCTION__, $fd);
    }
  }
}
