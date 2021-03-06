<?php
/**
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
* @copyright Copyright (c) 2009-2017 FluentDOM Contributors
*/
require __DIR__.'/../../vendor/autoload.php';

header('Content-type: text/plain');

$xml = <<<XML
<html>
  <head>
    <title>Examples: FluentDOM\Query::after()</title>
  </head>
  <body>
    <p>I would like to say: </p>
  </body>
</html>
XML;

echo FluentDOM($xml)
  ->find('//p')
  ->after('<b>Hello</b>')
  ->after(' World');