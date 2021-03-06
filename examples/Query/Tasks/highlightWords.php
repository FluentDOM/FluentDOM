<?php
/**
* Shows how to highlight several words in the body of a html document. It adds spans with specified
* classes around the found words/word parts.
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
* @copyright Copyright (c) 2009-2017 FluentDOM Contributors
*/

require __DIR__.'/../../../vendor/autoload.php';

$html = <<<HTML
<head>
  <!--
    The quick brown fox jumps over the lazy dog
  -->
  <body>
    The quick brown <span class="animal">fox</span> jumps over the lazy dog
  </body>
</head>
HTML;

$highlighter = new FluentDOMHighlighter(
  [
    'jump' => 'highlightOne',
    'The' => 'highlightTwo',
    'jumps' => 'highlightThree'
  ]
);

echo $highlighter->highlight(
  FluentDOM($html, 'text/html')->find('//body')
);

/**
* Highlights strings in text nodes, by wrapping them into a span with the given class
*/
class FluentDOMHighlighter {

  /**
  * Internal variable for the string => class name mapping
  * @var array
  */
  protected $_highlights = [];
  /**
  * Created pattern to match and split the content of the text nodes
  * @var string
  */
  protected $_pattern = '';

  /**
  * Create highlighter and set string => class name mapping
  *
  * @param array $highlights
  * @return FluentDOMHighlighter
  */
  public function __construct(array $highlights) {
    $this->setHighlights($highlights);
  }

  /**
   * Apply Highlight to FluentDOM selection
   *
   * @param FluentDOM\Query $fd
   *
   * @return FluentDOM\Query
   */
  public function highlight(FluentDOM\Query $fd): \FluentDOM\Query {
    $fd
      ->find('descendant-or-self::text()')
      ->each([$this, 'replace']);
    return $fd->spawn();
  }

  /**
  * Set string => class name mapping
  *
  * @param array $highlights
  */
  public function setHighlights(array $highlights) {
    uksort($highlights, [$this, 'compareLength']);
    $this->_highlights = [];
    $pattern = '';
    foreach ($highlights as $string => $className) {
      $key = strtolower($string);
      $this->_highlights[$key] = $className;
      $pattern .= '|'.preg_quote($key);
    }
    $this->_pattern = '(('.substr($pattern, 1).'))ui';
  }

  /**
  * Compare string by length, longer strings first
  *
  * @param string $stringOne
  * @param string $stringTwo
  * @return integer
  */
  public function compareLength($stringOne, $stringTwo) {
    $lengthOne = strlen($stringOne);
    $lengthTwo = strlen($stringTwo);
    if ($lengthOne > $lengthTwo) {
      return -1;
    } elseif ($lengthOne < $lengthTwo) {
      return 1;
    } else {
      return strcmp($stringOne, $stringTwo);
    }
  }

  /**
  * Check text node content for strings, split them by the strings, and replace strings with
  * a node structure. By using the DOM functions and not a somple replace, we can avoid
  * any problems with encodings.
  *
  * @param DOMText $node
  */
  public function replace(DOMText $node) {
    if (preg_match($this->_pattern, $node->nodeValue)) {
      // split using the pattern, but capture the delimiter strings
      $parts = preg_split(
        $this->_pattern, $node->nodeValue, -1, PREG_SPLIT_DELIM_CAPTURE
      );
      $items = [];
      foreach ($parts as $part) {
        $string = strtolower($part);
        if (isset($this->_highlights[$string])) {
          $span = $node->ownerDocument->createElement('span');
          $items[] = FluentDOM($span)
            ->addClass($this->_highlights[$string])
            ->text($part)
            ->item(0);
        } else {
          $items[] = $node->ownerDocument->createTextNode($part);
        }
      }
      // replace the text node
      FluentDOM($node)->replaceWith($items);
    }
  }
}
