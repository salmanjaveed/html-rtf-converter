<?php
/**
 * Created by PhpStorm.
 * User: stoffel
 * Date: 04.03.19
 * Time: 17:37
 */

namespace HtmlToRtf\Node\ElementNode;

use HtmlToRtf\Node\ElementNode;

/**
 * Class ThElementNode
 * @package HtmlToRtf\Node\ElementNode
 *
 * Parses a <th> HTML-element and creates the necessary
 * RTF string
 */
class ThElementNode extends ElementNode
{
    /**
     * parse node and create RTF string
     * @return string
     */
    public function parse()
    {
        $this->setRtfPrepend('\pard\intbl\qc ');
        $this->setRtfAppend('\cell');

        return $this->getRtfPrepend()
            . '{\b ' . $this->parseNodeChildren() . '}'
            . $this->getRtfAppend();
    }
}
