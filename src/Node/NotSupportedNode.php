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
     * Child nodes within this DOM-node will be ignored!
     * @return string
     */
    public function parse()
    {
        return '';
    }
}
