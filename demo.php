<?php

require_once("googleSearchParser.php");

$resultData = array();

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

?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Google Search Parser</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<style>
		#result {
			padding: 10px 0;
		}
	</style>
</head>
<body>
	<section id="result">
		<div class="container-fluid">
			<?php if ($resultData) : ?>
				<?php foreach ($resultData as $key => $val) : ?>
					<div class="row">
						<div class="col-sm-12">
							<div class="list-group">
							  <a href="<?=$val['url']?>" class="list-group-item" target="_blank">
							    <h4 class="list-group-item-heading"><?=$key+1?> . <?=$val['title']?></h4>
							    <p class="list-group-item-text"><?=$val['description']?></p>
							  </a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<h1>no data</h1>
			<?php endif; ?>
		</div>
	</section>
</body>
</html>



