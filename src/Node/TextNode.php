<?php

namespace HtmlToRtf\Node;

use HtmlToRtf\Node;

/**
 * Class TextNode
 * @package HtmlToRtf\Node
 */
class TextNode extends Node
{
    /**
     * parse the node and return RTF string
     * @return string
     */
    public function parse()
    {
        return $this->sanitizeString($this->getDomNode()->nodeValue);
    }

    /**
     * @return \DOMNode
     */
    protected function getDomNode()
    {
        return parent::getDomNode();
    }
}
