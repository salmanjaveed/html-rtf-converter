<?php

namespace HtmlToRtf;

/**
 * Class HtmlToRtf
 * @package HtmlToRtf
 * Basic converter class
 * Parses given HTML code and converts it to RTF code
 * Basic references and documentation can be found here:
 * - https://www.safaribooksonline.com/library/view/RTF+Pocket+Guide/9781449302047/ch01.html
 * - http://www.pindari.com/rtf1.html
 * - http://www.biblioscape.com/rtf15_spec.htm
 */
class HtmlToRtf
{
    /**
     * @var array ESCAPE_CHARS
     */
    const ESCAPE_CHARS = [
        '/\\\/' => '\\\\\\',
        '/\{/' => '\{',
        '/\}/' => '\}'
    ];

    /**
     * @var \DOMDocument $_doc
     */
    private $_doc;

    /**
     * the name of the file for downloading
     * @var string $_filename
     */
    private $_filename = 'test.rtf';

    /**
     * HtmlToRtf constructor.
     * @param mixed $html HTML text
     */
    public function __construct($html)
    {
        $this->_doc = new \DOMDocument();
        $this->setHtml($html);
    }

    /**
     * convert encoding of the HTML code to utf8
     * and escape some characters
     * @param mixed $html
     */
    public function setHtml($html)
    {
        //http://stackoverflow.com/questions/11309194/php-domdocument-failing-to-handle-utf-8-characters
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'utf-8');

        //escape reserved chars
        $html = preg_replace(array_keys(self::ESCAPE_CHARS), array_values(self::ESCAPE_CHARS), $html);

        $this->getDoc()->loadHTML($html);
        $this->_removeEmptyTextNodes($this->getDoc());
    }

    /**
     * get DOMDocument object
     * @return \DOMDocument
     */
    public function getDoc()
    {
        return $this->_doc;
    }

    /**
     * remove empty nodes from the DOMDocument
     * @param $DOMNode
     */
    private function _removeEmptyTextNodes($DOMNode)
    {
        if (!($DOMNode instanceof \DOMNode)) {
            return;
        }

        if ($DOMNode instanceof \DOMText && trim($DOMNode->nodeValue) === '') {
            $this->_removeEmptyTextNodes($DOMNode->nextSibling);
            $DOMNode->parentNode->removeChild($DOMNode);
        } else {
            $this->_removeEmptyTextNodes($DOMNode->nextSibling);
            $this->_removeEmptyTextNodes($DOMNode->firstChild);
        }
    }

    /**
     * stream the final RTF text as file to the browser
     */
    public function getRTFFile()
    {
        $rtf = $this->getRTF();
        /*header("Content-type: application/rtf");
        header("Content-Disposition: attachment; filename={$this->_filename}");*/
        echo $rtf;
        exit();
    }

    /**
     * just get the RTF text
     * @return string
     */
    public function getRTF()
    {
        $node = Node::getInstance($this->getDoc());
        return $node->parse();
    }

    /**
     * the name for the download file
     * @param string $name
     * @return mixed
     */
    public function setFileName($name)
    {
        $this->_filename = $name;
        return $this;
    }

    /**
     * get the current file name
     * @return string
     */
    public function getFileName()
    {
        return $this->_filename;
    }
}

/**
 * register autloader to load classes
 */
spl_autoload_register(function ($class) {
    $parts = explode('\\', $class);
    $found = ($parts[0] === 'HtmlToRtf');
    if ($found) {
        array_shift($parts);
        $fileName = __DIR__ . '/' . implode('/', $parts) . '.php';
        $found = file_exists($fileName);
        if ($found) {
            include $fileName;
        }
    }
    return $found;
});
