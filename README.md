# googleSearchParser

get google search data

version 1.0

# How to use

download 
1. googleSearchParser.php
2. simple_html_dom.php

```php

require_once("googleSearchParser.php");

$gsp = new GoogleSearchParser();

//can Accept string or array
$gsp->setKeyword('taiwan');

//array like this
//$gsp->setKeyword(array('taiwan', 'kaohsiung'));

//the value of the Accept-Language http header
$gsp->setLanguage('en');

//set which page u want get
$gsp->setPage(1);
$resultData = $gsp->getSearch();

//and you can get 5 pages once, just set loop
// for ($i=1; $i <= 5; $i++) { 
//  	$resultData = array_merge($resultData, $gsp->setPage($i)->getSearch());
// } 

```
