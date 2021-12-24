<!DOCTYPE HTML>
<html lang="en-US">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
div {
  font-size: 24px;
  padding-left: 115px;
}
</style>
</head>
<body>
<br>
<div>
Alt.Magick<br><br>
</div>
<a href="./secrets/"><img src="./files/star.png" style="padding-left: 30px;"></img></a><br><br><br>
<div style="padding-left: 45px;">
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">

  First &nbsp; <input type="text" name="first"><br><br>
  Last &nbsp; <input type="text" name="total"><br><br>
  <input type="submit" value="Browse"> &nbsp; &nbsp; or &nbsp; &nbsp; [<a href="search.php">Search</a>]<br><br>
</form>

</div>
<div style="padding-left: 30px;">
<?php
$start = 0;
$number = 0;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$start = $_POST['first'];
$number = $_POST['total'];
}
$exFiles = 5 //For system files
$filelist = scandir('/var/www/html/alt-magick.com/public_html/');
//get subset of file array
$selectedFiles = array_slice($filelist, count($filelist) - 5 - $number,($number-$start)+1);
//output appropriate items
$numposts = $start;
if($start > 0 && $number >0){
	foreach( array_reverse($selectedFiles) as $file)
	{
	    $link = str_replace("_"," ",$file);
	    echo $numposts . ". &nbsp <a href='" . $file . "' target='_blank'>" . $link . "</a><br><br>";
	    $numposts = $numposts + 1;
	}
}
?>
</div>	
<p style="padding-left: 110px;">Hosted on <a href="https://www.d-elete.com">D-Elete</a>,<br>
Source at <a href="https://github.com/powercrypt/Usenet-Archiving-Tool">GitHub</a></p>
</body>
</html>
