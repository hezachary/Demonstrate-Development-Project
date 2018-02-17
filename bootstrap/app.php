<?php
date_default_timezone_set('Australia/Sydney');

/**
 * Dev mode
 */
ini_set('display_startup_errors',1);
ini_set('display_errors',1);

/**
 * Prod mode
 *
 * No .env supported yet, can easily add into \App
 * such as: \App::get()->importEnv(dev.env or prod.env, default .env)
 */
//error_reporting(-1);
//error_reporting( E_ALL ^ E_NOTICE );

/** BASE DIR PATH */
define('ROOTPATH', realpath(__DIR__.'/../'));

/** BASE URL PATH */
define('BASEURLPATH', '/test/');

/** Includes DIR PATH */
define('INCLUDESPATH', ROOTPATH . DIRECTORY_SEPARATOR . 'includes');
/** Includes DIR PATH */
define('TEMPLATEPATH', ROOTPATH . DIRECTORY_SEPARATOR . 'template');

/** Load spl_autoload_register for class loading */
require_once( INCLUDESPATH . DIRECTORY_SEPARATOR . 'ini.php' );

$app = App::get();

/**
 * base url path
 **/
$app->setMeta('BASEURLPATH', '/test/');

/**
 * the data provider, please change to \Provider\GoogleProvider::class
 * if google has not block your ip address
 **/
//$app->setRepo(\Provider\Base::class, \Provider\FakeGoogleProvider::class);
$app->setRepo(\Provider\Base::class, \Provider\GoogleProvider::class);

/**
 * total records need to pull
 **/
$app->setMeta(
  \Model\Search::class,
  [
    'total' => 20,
  ]
);

/**
 * the place we store fake data
 **/
$app->setMeta(
  \Provider\FakeGoogleProvider::class,
  [
    'file' => ROOTPATH . DIRECTORY_SEPARATOR . 'Fake' . DIRECTORY_SEPARATOR . 'FakeGoogleResult.html',
  ]
);

/**
 * for google search settings
 */
$app->setMeta(
  \Provider\GoogleProvider::class,
  [
    //in case we like change to other region us, uk ...etc
    'HOST' => 'https://www.google.com.au',
    'retrieve' => function($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($ch);
    },
    //Closure for custom way filter data
    'filter' => function ($output) {
        return preg_replace(['/\r|\n/', '/^.*<body[^>]*>/', '/<\/body>\s*<\/html>\s*/', '/<script/', '/<\/script>/'], [' ', '', '', '<data-script', '</data-script>'], $output);
    }
  ]
);

/**
 * Default Keywords
 * change to any keywords you like to search
 **/
$app->setMeta('DEFAULT_KEYWORDS', 'NSW');

/**
 * Default Match words
 * change to any match words you like to compare
 **/
$app->setMeta('DEFAULT_MATCHWORDS', 'nsw.gov.au');

/**
 * Author
 * Hope you won't take the credit ;-)
 **/
$app->setMeta('AUTHOR', 'Zachary He');

/**
 * add a defalut router since we only have one page
 **/
$app->setRouter('Default', \Controller\HomeController::class);

/**
 * most setMeta/setRouter, can be also sit into /config, such as:
 * config/meta.config.php
 * config/router.config.php
 * config/repo.config.php
 *
 * then, $app->loadConfig('meta'); $app->loadConfig('router'); ... etc
 */
return $app;