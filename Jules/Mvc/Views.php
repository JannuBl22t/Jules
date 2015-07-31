<?php

namespace Jules\Mvc;

class Views
{
    private $controller = null;
    private $tag = null;
    private $loader = null;

    private $viewsDir = null;
    private $content = null;

    private $customVars = array();

    public function __construct($controller, $loader, $tag)
    {
        $this->controller = $controller;
        $this->loader = $loader;
        $this->tag = $tag;

        $this->viewsDir = $this->loader->getDirs()['views'];
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        $contentFile = $this->viewsDir
            .str_replace('Controller', '', $this->controller->Jules_getClass())
            .DIRECTORY_SEPARATOR
            .str_replace('Action', '', $this->controller->Jules_getMethod()).'.phtml';

        if(file_exists($contentFile))
        {
            ob_start();

            foreach($this->getCustomVars() as $key => $val)
            {
                $$key = $val;
            }

            include($contentFile);

            $page = ob_get_contents();

            ob_end_clean();

            return $page;
        }
        else
        {
            return $this->content;
        }
    }

    private function loadContent()
    {
        $sharedFile = $this->viewsDir.'index.phtml';

        if(file_exists($sharedFile))
        {
            require_once($sharedFile);
        }
    }

    public function render()
    {
        ob_start();

        $this->loadContent();

        $page = ob_get_contents();

        ob_end_clean();

        return $page;
    }

    private function getCustomVars()
    {
        return $this->customVars;
    }

    public function setVar($name, $value)
    {
        $this->customVars[$name] = $value;
    }
}