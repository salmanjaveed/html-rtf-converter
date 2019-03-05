<?php
/**
 * Created by PhpStorm.
 * User: stoffel
 * Date: 04.03.19
 * Time: 17:37
 */

namespace HtmlToRtf\Node\ElementNode;

use \HtmlToRtf\Node\ElementNode;

/**
 * Class TdElementNode
 * @package HtmlToRtf\Node\ElementNode\TableElementNode
 */
class TdElementNode extends ElementNode
{
    /**
     * parse node and prepare RTF commands
     * @return string
     */
    public function parse()
    {
        $this->setRtfPrepend('\pard\intbl ');
        $this->setRtfAppend('\cell ');

        return parent::parse();
    }

}
