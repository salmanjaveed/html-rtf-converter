<?php
/**
 * Created by PhpStorm.
 * User: stoffel
 * Date: 04.03.19
 * Time: 17:00
 */

namespace HtmlToRtf\Node\ElementNode;

use \HtmlToRtf\Node\ElementNode;

/**
 * Class TrElementNode
 * @package HtmlToRtf\Node\ElementNode\TableElementNode
 */
class TrElementNode extends ElementNode
{
    /**
     * basic indent size for text from
     * left border to the cell text
     */
    private $_indentSize = '144';

    /**
     * parse node and prepare RTF string
     * @return string
     */
    public function parse()
    {
        $prepend = '\trowd\trgaph'. $this->_indentSize;

        $children = $this->getDomNode()->childNodes;
        $cellWidth = ceil(9000 / $children->length);

        for($i = 0; $i < $children->length; $i++) {
            $prepend .= '\cellx' . ($i + 1) * $cellWidth;
        }

        $this->setRtfPrepend($prepend . ' ');
        $this->setRtfAppend('\row');
        return parent::parse();
    }
}
