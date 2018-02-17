TASK:

1. A small application will search google for keywords and return the result to the
   screen.
1.1. Receives a string of keywords (E.G. "Zac") and a URL (E.G. "www.zacharyhe.com").
2. This is then processed to return a list of numbers for where the resulting URL is
   found in the Google results.
3. No frameworks are to be used
4. Please use at least one design pattern when coding this solution. Clearly comment
   why this design pattern was used (or include a note in your submission).

Result:

1. Use php to retrieve data from google, or fake file (for demo only, since google
   block IP for heavy scraping)
2. Use javascript for process returned HTML, and flush to browser

Requirements:

1. PHP7
2. Internet

How to:

1. Copy the code to any dev site /test/ directory, make sure you access the page via: http://xxxxx/test/index.php
2. Type both search keywords and url inside the search box
3. Click "Submit"

More detail:

Please take close look of /bootstrap/app.php

1. Change default search box text, please change ```$app->setMeta('DEFAULT_KEYWORDS', 'NSW');```
2. Change default match box text, please change ```$app->setMeta('DEFAULT_MATCHWORDS', 'nsw.gov.au');```
3. Current demo use GoogleProvider, if your IP is blocked by google, please change from:
```
$app->setRepo(\Provider\Base::class, \Provider\GoogleProvider::class);
```
to
```
$app->setRepo(\Provider\Base::class, \Provider\FakeGoogleProvider::class);
```
4. Current script search against google.com.au, however, it can also search other google region, please change:
```
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
```
5. Change total scraping result, please change:
```
$app->setMeta(
  \Model\Search::class,
  [
    'total' => 20,
  ]
);
```

Design pattern used:

1. MVC - for load the page, and ajax
2. Singleton - for App, app does not need to be recreated all the time
3. Strategy - for search against data provide, in case google block the IP, by using 
   the Fake provide to prove code working (it is not how production application suppose
   to work, Fake in current project is nothing but prove of concept for using Strategy 
   Pattern, otherwise, it should be part of the Unit Testing).
4. Repository - for data provide, so application can change the provider by shifting 
   repo config
5. Dependency injection - for controller, to avoid hard coded object inside method 
   creates difficulties on unit test

Design pattern was tried to use, but not:

1. Factory - for Search class, reason not use was Search class currently only be
   used in Controller, and it is used as Dependency injection, if I need to cover
   the Factory with Dependency injection, I need add more settings in app.php such
   as: ```$app->setFactory(\Model\Search::class, 'create');```, and add more code
   to App class, it is kind overkilled
2. Observe - for google search result counting, or error log, reason not use was I
   choose Browser JS to populate data, it is more reliable

Features thought about, but not implement:

1. load config file by $app (easy to do, just and an extra method in App class and
   by supplied name to locate file, then ```$this->app[$name] = include $filepath；``` )
2. Dependency injection support recursive load object also cover custom variable
   (it is overkilled for application this level)
3. Router (it is overkilled for application this level)
4. Create /public/ directory, so index.php and assets are all in public (it can be
   problem if for anyone try to have a quick setup and see the application run)
5. Add middle-ware to data provider, such as: Proxy, so scraping can bypass google IP 
   block (then it bring up another question of scraping proxy list ...)

About Solution:

Use php curl to scraping google, and use js to parsing html data. I did not use 
multi-curl or JS async call (such as: 10 ajax/curl call in one go), was google will 
block the IP very quickly.

About Alternative Solutions:

1. GreaseMonkey script

   - POS: very small, JS only
   
   - CON: very limit design patterns can be applied, require install Browser plugin
   
2. Remote host JS, browser console includes and executes (js can create a invisible
   iframe, since the JS is inject after google page load, there is no cross region
   issue, js can read and write to current google page freely)
   
   - POS: smallest, JS only
   
   - CON: more towards developer, require open google page and search first
   
3. Scraping Data by google custom search API

   - POS: data structure more reliable, easy to setup search machine in API console
   
   - CON: require share API token, costs may be involved
   
4. Scraping Data by google search, process return data by php DOMDocument/SimpleXML

   - POS: backend code only, no browser involved, can become a console script run by
   cron, report by daily
   
   - CON: no frontend JS involved (bad for presentation ;-\), if HTML is in bad format
   the parsing can easily failed
   
5. Scraping Data from 3rd party provider port to google

   - POS: may bypass google IP block policy
   
   - CON: security reason, also flooding independent individual server make me feel
   bad

Technology used:

1. Custom PHP MVC
2. AJAX
4. Bootstrap
5. jQuery
6. HTML5