<!DOCTYPE HTML>
<html lang="en-US">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
div {
  font-size: 24px;
  padding-left: 110px;
}

</style>
</head>
<body>
<br>
<div>
Alt.Magick<br>
<br>
</div>
<a href="./secrets/"><img src="./files/star.png" style="padding-left:25px;"></img></a><br>
<br>
<div style="padding-left: 40px;">
<br>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
Find &nbsp; <input type="text" name="searchbox"><br><br><input type="submit" value="Search"> &nbsp; &nbsp; or &nbsp; &nbsp; [<a href="index.php">Browse</a>]<br><br>
</form>
</div>
<br>
<div style="padding-left: 25px;">
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $searched = $_POST["searchbox"];
        $s = filter_var($searched, FILTER_SANITIZE_STRING);
        $output = shell_exec('./search.py "' . $s . '"');
        echo $output;
        }
?>

</div>
<br><br>
<p style="padding-left: 80px;">Hosted on <a href="https://www.d-elete.com">D-Elete</a></p>
	
</body>
</html>
