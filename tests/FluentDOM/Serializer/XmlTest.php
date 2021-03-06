<?php
/*
 * FluentDOM
 *
 * @link https://thomas.weinert.info/FluentDOM/
 * @copyright Copyright 2009-2021 FluentDOM Contributors
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */

namespace FluentDOM\Serializer {

  use FluentDOM\DOM\Document;
  use FluentDOM\TestCase;

  require_once __DIR__ . '/../TestCase.php';

  class XmlTest extends TestCase  {

    public function testToString(): void {
      $document = new Document();
      $document->loadXML(self::XML);
      $serializer = new Xml($document);
      $this->assertXmlStringEqualsXmlString(
        self::XML, (string)$serializer
      );
    }
  }
}
