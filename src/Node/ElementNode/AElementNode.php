<?php

namespace HtmlToRtf\Node\ElementNode;

use HtmlToRtf\Node\ElementNode;

/**
 * Class AElementNode
 * @package HtmlToRtf\Node\ElementNode
 */
class AElementNode extends ElementNode
{
    /**
     * parse node and convert to RTF string
     * @return string
     */
    public function parse()
    {
        $url = $this->getAttribute('href');
        if (strlen($url) > 0) { //if no href just keep on parsing but ignore url
            $this->setRtfPrepend('{\field{\*\fldinst{HYPERLINK "' . $this->sanitizeString($url) . '"}}{\fldrslt{\ul\cf2 ');
            $this->setRtfAppend('}}}');
        }
        return parent::parse();
    }
}
