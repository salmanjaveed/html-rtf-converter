<?php

namespace HtmlToRtf\Node;

use HtmlToRtf\Node;

/**
 * Class ElementNode
 * @package HtmlToRtf\Node
 */
class ElementNode extends Node
{
    /**
     * @var string $_rtfPrepend rtf string to prepend
     */
    private $_rtfPrepend;

    /**
     * @var string $_rtfAppend rtf string to append
     */
    private $_rtfAppend;

    /**
     * @var null
     */
    private $_attributes = null;

    /**
     * ElementNode constructor.
     * @param $node
     * @param string $rtfPrepend code to prepend
     * @param string $rtfAppend code tp append
     */
    public function __construct($node, $rtfPrepend = '', $rtfAppend = '')
    {
        parent::__construct($node);
        $this->setRtfPrepend($rtfPrepend);
        $this->setRtfAppend($rtfAppend);
    }

    /**
     * pre-/append the RTF strings and parse child nodes
     * @return string
     */
    public function parse()
    {
        return $this->getRtfPrepend() . $this->parseNodeChildren() . $this->getRtfAppend();
    }

    /**
     * @return string
     */
    protected function getRtfPrepend()
    {
        return $this->_rtfPrepend;
    }

    /**
     * @param string $rtf set a string to prepend to RTF
     */
    protected function setRtfPrepend($rtf)
    {
        $this->_rtfPrepend = $rtf;
    }

    /**
     * @return string
     */
    protected function getRtfAppend()
    {
        return $this->_rtfAppend;
    }

    /**
     * @param string $rtf set string to append to RTF
     */
    protected function setRtfAppend($rtf)
    {
        $this->_rtfAppend = $rtf;
    }

    /**
     * get any attribute from the node
     * @param $name
     * @return mixed
     */
    public function getAttribute($name)
    {
        return $this->getAttributes()[$name];
    }

    /**
     * get all attributes for a node
     * @return array|null
     */
    public function getAttributes()
    {
        if ($this->_attributes === null) {
            foreach ($this->getDomNode()->attributes as $attribute) {
                $this->_attributes[$attribute->name] = $attribute->value;
            }
        }
        return $this->_attributes;
    }
}
