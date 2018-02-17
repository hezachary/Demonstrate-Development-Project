<?php
namespace Controller;

use \Model\Search;
use \Helper\Data;
use \Provider\Base as BaseProvider;

/**
 * HomeController
 * 
 * @package demo
 * @author Zhehai He
 * @copyright 2018
 * @version 0.1
 * @access public
 */
class HomeController extends BaseController
{

    /**
     * the data search provide
     * @var \Provider\Base
     */
    private $provider;

    /**
     * the search data container
     * @var \Model\Search
     */
    private $search;

    /**
     * HomeController constructor.
     *
     * @param \Provider\Base $provider
     * @param \Model\Search $search
     */
    public function __construct(BaseProvider $provider, Search $search)
    {
        $this->provider = $provider;
        $this->search = $search;
    }

    /**
     * HomeController::filter()
     * The filters group for form validation
     *
     * @param array $aryData
     * @param string $strField
     *
     * @return array $aryResultData
     **/
    protected function filter($aryData, $strField)
    {
        $strField = explode('::', $strField);
        $strField = array_pop($strField);
        $aryControlFilterList = [
          'ajax' => [
            'keywords' => [
              [
                'filter' => FILTER_SANITIZE_STRING,
                'options' => FILTER_FLAG_ENCODE_HIGH | FILTER_FLAG_ENCODE_LOW,
              ],
            ],
            'nextUrl' => [
              [
                'filter' => FILTER_SANITIZE_STRING,
                'options' => FILTER_FLAG_ENCODE_HIGH | FILTER_FLAG_ENCODE_LOW,
              ],
            ],
          ],
        ];

        return Data::filter($aryData,
          $aryControlFilterList[$strField]);
    }

    /**
     * index, default action
     **/
    public function index()
    {
        $this->strTemplateName = 'home';
        $this->aryExtra['total'] = $this->search->getTotal();
    }

    /**
     * ajax, accept POST only
     **/
    public function ajax()
    {
        if (!$_POST) {
            return;
        }

        $this->blnIsAjax = true;

        $this->aryRequest = $this->filter($_POST, __METHOD__);

        $this->search->setKeywords($this->aryRequest['keywords']);

        $this->provider->path = $this->aryRequest['nextUrl'];

        /**
         * strategy pattern, in case we have different data provider
         * such as: google vs fake google data
         */
        $this->aryExtra['raw'] = $this->search->retrieveData($this->provider);

        $this->strTemplateName = null;
    }
}