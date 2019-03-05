<?php

namespace HtmlToRtf\Node;

use HtmlToRtf\Node;

/**
 * Class NotSupportedNode
 * @package HtmlToRtf\Node
 *
 * Return an empty string for any unknown Node
 */
class NotSupportedNode extends Node
{
    /**
     * do nothing but return an empty string
     * @return string
     */
    public function parse()
    {
        return '';
    }
}
