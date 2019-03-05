<?php

namespace HtmlToRtf\Node\ElementNode;

use HtmlToRtf\Node\ElementNode;

/**
 * Class HTMLElementNode
 * @package HtmlToRtf\Node\ElementNode
 */
class HtmlElementNode extends ElementNode
{
    /**
     * @var float|int $_defaultFontSize
     */
    private $_fontSize = 12 * 2;

    /**
     * @var string $_fontStyle font style
     */
    private $_fontStyle = 'Calibri';

    /**
     * HtmlElementNode constructor.
     * In addition you can set the font style with the constructor
     * or use setFontStyle function to do it.
     *
     * @param $node
     * @param string $rtfPrepend
     * @param string $rtfAppend
     * @param string $fontStyle
     */
    public function __construct($node, $rtfPrepend = '', $rtfAppend = '', $fontStyle = 'Calibri')
    {
        $this->_fontStyle = $fontStyle;

        parent::__construct($node, $rtfPrepend, $rtfAppend);
    }

    /**
     * parse element and return RTF string
     *
     * @return string
     */
    public function parse()
    {
        //start rtf document
        $prepend = '{\rtf1\ansi\ansicpg1252';
        //set default fonts
        $prepend .= '\deff0{\fonttbl{\f0 ' . $this->_fontStyle . ';}}';
        //set hyperlink color
        $prepend .= '{\colortbl ;\red0\green0\blue0 ;\red0\green0\blue255 ;}';
        //set default style
        $prepend .= '\f0\cf1\fs' . $this->_fontSize . ' ';

        $append = '}';
        $this->setRtfPrepend($prepend);
        $this->setRtfAppend($append);
        return parent::parse();
    }

    /**
     * set font style
     * @param $fontName
     * @return $this
     */
    public function setFontStyle($fontName)
    {
        $this->_fontStyle = $fontName;
        return $this;
    }

    /**
     * get current font style
     * @return string
     */
    public function getFontStyle()
    {
        return $this->_fontStyle;
    }
}
