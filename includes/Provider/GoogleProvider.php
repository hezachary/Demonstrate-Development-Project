<?php
namespace Provider;

use \App;

/**
 * Google search result provider
 *
 * Use this strategy for google has not block the IP
 *
 * @package demo
 * @author Zhehai He
 * @copyright 2018
 * @version 0.1
 * @access public
 */
class GoogleProvider implements Base
{

    const BASE_URL = 'https://www.google.com.au';

    /**
     * url path + query
     *
     * @var string
     */
    private $path = '/search?q={data}';

    /**
     * store data
     *
     * @var array
     */
    protected $values = [];

    /**
     * set data
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->values[$name] = $value;
    }

    /**
     * get data
     *
     * @param $name
     *
     * @return mixed|void
     */
    public function __get($name)
    {
        if (isset($this->values[$name])) {
            return $this->values[$name];
        }
        return;
    }

    /**
     * use curl to retrieve data
     *
     * @param string $keywords
     *
     * @return string
     */
    protected function retrieve($keywords)
    {
        $url = str_replace('{data}', urlencode($keywords),
          App::get()->getMeta(self::class)['HOST'] . $this->path);

        /**
         * curl or file_get_content should not be put in here
         * otherwise, it will be hard to apply unit test
         */
        return App::get()->getMeta(self::class)['retrieve']($url);
    }

    /**
     * Allow custom filter to apply, otherwise, use default filter
     *
     * @param string $output
     *
     * @return string
     */
    protected function filter($output)
    {
        return App::get()->getMeta(self::class)['filter'] ?
          App::get()->getMeta(self::class)['filter']($output) :
          preg_replace([
            '/\r|\n/',
            '/^.*<body[^>]*>/',
            '/<\/body>\s*<\/html>\s*/',
            '/<script/',
            '/<\/script>/'
          ], [' ', '', '', '<data-script', '</data-script>'], $output);
    }

    /**
     * load result by keywords
     *
     * @param $keywords
     *
     * @return string
     */
    public function load($keywords): string
    {
        $output = $this->retrieve($keywords);
        $output = $this->filter($output);

        return utf8_decode($output);
    }
}