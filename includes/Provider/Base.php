<?php
namespace Provider;

/**
 * data provider interface
 *
 * @package demo
 * @author Zhehai He
 * @copyright 2018
 * @version 0.1
 * @access public
 */
interface Base
{
    /**
     * set data
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value);

    /**
     * get data
     *
     * @param $name
     *
     * @return mixed
     */
    public function __get($name);

    /**
     * load data result by the keywords
     *
     * @param $keywords
     *
     * @return string
     */
    public function load($keywords) : string;
}