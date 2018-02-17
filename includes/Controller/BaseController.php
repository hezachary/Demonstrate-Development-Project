<?php
namespace Controller;
use App;
/**
 * BaseController
 * Extended by all controllers
 * 
 * @package demo
 * @author Zhehai He
 * @copyright 2018
 * @version 0.1
 * @access public
 */
class BaseController
{

    /**
     * response in AJAX or not
     *
     * @var bool
     */
    public $blnIsAjax = false;

    /**
     * extra data for response
     * useful for ajax
     *
     * @var array
     */
    public $aryExtra = [];

    /**
     * filtered request/post/get data
     *
     * @var array
     */
    public $aryRequest = [];

    /**
     * Load Template
     *
     * @param string $strTemplateName
     * @param boolean $echo
     *
     * @return  string
     */
    public function loadTemplate($strTemplateName = null, $echo = false)
    {
        $strTemplateName = $strTemplateName ? $strTemplateName : $this->strTemplateName;
        if (!$strTemplateName) {
            return;
        }
        $file = TEMPLATEPATH . DIRECTORY_SEPARATOR . $strTemplateName . '.php';

        if (!file_exists($file)) {
            return;
        }

        $app = App::get();

        ob_start();
        include $file;
        $html = ob_get_clean();

        if ($echo) {
            echo $html;
        } else {
            return $html;
        }
    }

    /**
     * Render Template
     * For requests from page actions
     *
     * @param boolean $echo
     *
     * @return string
     */
    public function render($echo = true)
    {
        if (!$this->blnIsAjax) {
            $html = $this->loadTemplate(false);
            if ($echo) {
                echo $html;
            }
            return $html;
        }

        $aryExport = [];

        if (is_array($this->aryExtra)) {
            $aryExport = array_merge($aryExport, $this->aryExtra);
        }

        $strExport = json_encode($aryExport);

        if ($echo) {
            echo $strExport;
        } else {
            return $strExport;
        }
    }

    /**
     * Get Request Data
     *
     * @param string $strKey
     *
     * @return array/null
     */
    public function getRequest($strKey)
    {
        return array_key_exists($strKey,
          $this->aryRequest) ? $this->aryRequest[$strKey] : null;
    }
}
