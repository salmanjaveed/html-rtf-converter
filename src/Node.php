<?php

namespace HtmlToRtf;

use HtmlToRtf\Node\ElementNode;
use HtmlToRtf\Node\NotSupportedNode;
use HtmlToRtf\Node\TextNode;
use HtmlToRtf\Utils\UnicodeUtils;

/**
 * Class Node
 * @package HtmlToRtf
 */
class Node
{
    /**
     * array containing the node elements
     * @var array $_nodeStore
     */
    private static $_nodeStore = [];

    /**
     * @var \DOMNode $_domNode
     */
    private $_domNode;

    /**
     * Construct a Node
     * @param mixed $aDomNode
     */
    public function __construct($aDomNode)
    {
        $this->_domNode = $aDomNode;
    }

    /**
     * get parent element for the current node
     * @return Node
     */
    public function getParent()
    {
        return Node::getInstance($this->getDomNode()->parentNode);
    }

    /**
     * @return string
     */
    protected function parseNodeChildren()
    {
        $rtf = '';
        foreach ($this->getDomNode()->childNodes ?: [] as $childNode) {
            $node = self::getInstance($childNode);
            $rtf .= $node->parse();
        }
        return $rtf;
    }

    /**
     * @return \DOMNode
     */
    protected function getDomNode()
    {
        return $this->_domNode;
    }

    /**
     * @param \DOMNode $node
     * @return Node
     */
    public static function getInstance($node)
    {
        if (!isset($node)) {
            return null;
        }
        $instanceId = spl_object_hash($node);
        if (!isset(self::$_nodeStore[$instanceId])) {
            $nodeSpecific = self::_createSpecificNode($node);
            self::$_nodeStore[$instanceId] = $nodeSpecific;
        }
        return self::$_nodeStore[$instanceId];
    }

    /**
     * @return string
     */
    public function parse()
    {
        return $this->parseNodeChildren();
    }

    /**
     * Convert all unicode chars to \uXXXX?
     * @param String $string unicode string
     * @return string
     */
    protected function sanitizeString($string)
    {
        $stringNew = '';
        $stringArray = UnicodeUtils::strSplit($string);
        foreach ($stringArray as $char) {
            $code = ord($char);
            if ($code < 128) {
                $chr = chr($code);
            } else {
                $chr = '\u' . str_pad(UnicodeUtils::charToUnicodeOrd($char), 4, '0', STR_PAD_LEFT) . '?';
            }
            $stringNew .= $chr;
        }

        return $stringNew;
    }

    /**
     * @return array
     */
    protected function getNodePath()
    {
        return preg_split('/\//', $this->getDomNode()->getNodePath());
    }

    /**
     * @param \DOMNode $node
     * @return Node|null returns
     */
    private static function _createSpecificNode(\DOMNode $node)
    {
        $nodeSpecific = null;

        switch ($node->nodeType) {
            //HTML element node
            case XML_ELEMENT_NODE:
                /**
                 * @var \DOMElement $node
                 */
                switch ($node->tagName) {
                    case 'body':
                        $nodeSpecific = new Node($node);
                        break;
                    case 'b':
                    case 'strong':
                        $nodeSpecific = new ElementNode($node, '{\b ', '}');
                        break;
                    case 'i':
                    case 'em':
                        $nodeSpecific = new ElementNode($node, '{\i ', '}');
                        break;
                    case 'u':
                        $nodeSpecific = new ElementNode($node, '{\ul ', '}');
                        break;
                    case 'br':
                        $nodeSpecific = new ElementNode($node, '\line ');
                        break;
                    case 'html':
                    case 'a':
                    case 'ol':
                    case 'ul':
                    case 'li':
                    case 'p':
                    case 'span':
                    case 'table':
                    case 'thead':
                    case 'tbody':
                    case 'tr':
                    case 'th':
                    case 'td':
                        $class = '\\HtmlToRtf\\Node\\ElementNode\\' . ucfirst(strtolower($node->tagName)) . 'ElementNode';
                        $nodeSpecific = new $class($node);
                        break;

                    //TODO: html special chars (&mbsp; => \~)

                    default:
                        $nodeSpecific = new NotSupportedNode($node);
                }
                break;

            //Plaintext nodes
            case XML_TEXT_NODE:
                $nodeSpecific = new TextNode($node);
                break;

            //start document type nodes
            case XML_HTML_DOCUMENT_NODE:
            case XML_DOCUMENT_TYPE_NODE:
                $nodeSpecific = new Node($node);
                break;

            //remove non supported nodes
            default:
                $nodeSpecific = new NotSupportedNode($node);
                break;
        }

        return $nodeSpecific;
    }
}
