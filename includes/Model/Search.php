<?php
namespace Model;

use Provider\Base;
use App;

/**
 * Search Model
 *
 * @package demo
 * @author Zhehai He
 * @copyright 2018
 * @version 0.1
 * @access public
 */
class Search
{
    /**
     * keywords
     * @var string
     */
    private $keywords;

    /**
     * Search constructor.
     */
    public function __construct()
    {
        $this->setTotal();
    }

    /**
     * set keywords
     * @param string $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * set total records need to pull
     */
    public function setTotal()
    {
        $this->total = App::get()->getMeta(self::class)['total'];
    }

    /**
     * get total records need to pull
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * retrieve data by supplied strategy
     *
     * @param \Provider\Base $objSource
     *
     * @return string
     */
    public function retrieveData(Base $objSource)
    {
        return $objSource->load($this->keywords);
    }
}