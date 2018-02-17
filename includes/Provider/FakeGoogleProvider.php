<?php
namespace Provider;

use \App;

/**
 * Fake google search result provider
 *
 * Only use this strategy for demo, in case google block the IP
 *
 * @package demo
 * @author Zhehai He
 * @copyright 2018
 * @version 0.1
 * @access public
 */
class FakeGoogleProvider extends GoogleProvider implements Base
{

    /**
     * load data result from static file
     *
     * @param string $keywords
     *
     * @return string
     */
    public function load($keywords): string
    {
        $output = file_get_contents(App::get()->getMeta(self::class)['file']);
        $output = $this->filter($output);

        return $output;
    }
}