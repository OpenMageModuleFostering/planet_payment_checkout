<?php
namespace PlanetLib;
use PlanetLib\Writer\WriterSession;

/**
 * Class for obtaining form and script for including js
 *
 * Class View
 * @package PlanetLib
 */
class View
{
    protected $src;

    protected $language;
    protected $style;
    protected $brands;

    protected $action;

    protected $writer;

    /**
     * @param WriterInterface $writer
     */
    public function __construct(WriterInterface $writer = null)
    {
        if (file_exists("config.ini")) {
            $config = parse_ini_file("config.ini", true);

            $this->setSrc($config['view']['src']);
            $this->setStyle($config['view']['style']);
            $this->setLanguage($config['view']['language']);
            $this->setBrands($config['view']['brands']);
        }

        if (is_null($writer)) {
            $writer = new WriterSession();
        }
        $this->writer = $writer;
    }

    /**
     * Method for obtaining form. Parameters are obtained from config, but can be configured manually
     *
     * @param null|string $token - If token is null, it will be obtained from Writer (by default from session writer)
     * @param bool $includeScript - If true, script will be connected here, else it can be obtained using getScript
     * @return string
     */
    public function getForm($token = null, $includeScript = false)
    {
        if (is_null($token)) {
            $token = $this->writer->readToken();
        }

        if ($includeScript) {
            $html = $this->getScript($token);
        }
        else {
            $html = '';
        }

        $html .= '<form action="' . $this->action . '" class="'.paymentWidgets.'">';
            foreach ($this->brands as $brand) {
                $html.=$brand .' ';
            }
        $html .= '</form>';

        return $html;
    }

    /**
     * Method for getting script which includes js
     *
     * @return string
     */
    public function getScript($token = null)
    {
        if (is_null($token)) {
            $token = $this->writer->readToken();
        }
        $html = '<script src="' . $this->src . '?checkoutId=' . $token;
        $html .= '"></script>';
        return $html;
    }

    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @param string $style
     * @return $this
     */
    public function setStyle($style)
    {
        $this->style = $style;
        return $this;
    }

    /**
     * @param array $brands
     * @return $this
     */
    public function setBrands($brands)
    {
        $this->brands = $brands;
        return $this;
    }

    /**
     * @param string $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @param string $src
     * @return $this
     */
    public function setSrc($src)
    {
        $this->src = $src;
        return $this;
    }
} 