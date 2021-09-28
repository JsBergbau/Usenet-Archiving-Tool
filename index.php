<?php

	// MINIXED is a minimal but nice-looking PHP directory indexer.
	// More at https://github.com/lorenzos/Minixed

	// =============================
	// Configuration                
	// =============================
	
	$browseDirectories = false; // Navigate into sub-folders
	$title = 'alt.magick';
	$subtitle = '{{size}} total'; // Empty to disable
	$slogan = 'For discussion about supernatural arts.';
	$breadcrumbs = false; // Make links in {{path}}
	$showParent = false; // Display a (parent directory) link
	$showDirectories = false;
	$showDirectoriesFirst = true; // Lists directories first when sorting by name
	$showHiddenFiles = false; // Display files starting with "." too
	$alignment = 'left'; // You can use 'left' or 'center'
	$showIcons = true;
	$dateFormat = 'm / d / y'; // Used in date() function
	$sizeDecimals = 1;
	$showFooter = false; // Display the "Powered by" footer
	$openIndex = $browseDirectories && true; // Open index files present in the current directory if $browseDirectories is enabled
	$browseDefault = null; // Start on a different "default" directory if $browseDirectories is enabled
	
	// =============================
	// =============================
	
	// Who am I?
	$_self = basename($_SERVER['PHP_SELF']);
	$_path = str_replace('\\', '/', dirname($_SERVER['PHP_SELF']));
	$_total = 0;
	$_total_size = 0;
	
	// Directory browsing
	$_browse = null;
	if ($browseDirectories) {
		if (!empty($browseDefault) && !isset($_GET['b'])) $_GET['b'] = $browseDefault;
		$_GET['b'] = trim(str_replace('\\', '/', @$_GET['b']), '/ ');
		$_GET['b'] = str_replace(array('/..', '../'), '', @$_GET['b']); // Avoid going up into filesystem
		if (!empty($_GET['b']) && $_GET['b'] != '..' && is_dir($_GET['b'])) $_browse = $_GET['b'];
	}
	
	// Index open
	if (!empty($_browse) && $openIndex) {
		$_index = null;
		if (file_exists($_browse . "/index.htm")) $_index = "/index.htm";
		if (file_exists($_browse . "/index.html")) $_index = "/index.html";
		if (file_exists($_browse . "/index.php")) $_index = "/index.php";
		if (!empty($_index)) {
			header('Location: ' . $_browse . $_index);
			exit();
		}
	}

	// Encoded images generator
	if (!empty($_GET['i'])) {
		header('Content-type: image/png');
		switch ($_GET['i']) {
			case       'asc': exit(base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAcAAAAHCAYAAADEUlfTAAAAFUlEQVQImWNgoBT8x4JxKsBpAhUAAPUACPhuMItPAAAAAElFTkSuQmCC'));
			case      'desc': exit(base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAcAAAAHCAYAAADEUlfTAAAAF0lEQVQImWNgoBb4j0/iPzYF/7FgCgAADegI+OMeBfsAAAAASUVORK5CYII='));
			case 'directory': exit(base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAASklEQVQYlYWPwQ3AMAgDb3Tv5AHdR5OqTaBB8gM4bAGApACPRr/XuujA+vqVcAI3swDYjqRSH7B9oHI8grbTgWN+g3+xq0k6TegCNtdPnJDsj8sAAAAASUVORK5CYII='));
			case      'file': exit(base64_decode('iVBORw0KGgoAAAANSUhEUgAAAA4AAAAOCAYAAAAfSC3RAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAAGYktHRAD/AP8A/6C9p5MAAAD1SURBVDhPxZI9ioRAEIVfqxho4AnEyEgwEzyAJxC9iFcw9CBewTOYiYGhf4GpCIoo2jvdO7vMwM7uYLIfVFCPelR1dRF6AxfgxqZp0Pc9F1RVhW3bkCSJ568g27ZR13Wh6zoEQcAwDIiiCGEY3kt+hizLQhVFQRzH3JhlGaqqQhAEvOvjS87zhOd58H0fZJ5nysZL0xTjOEKWZYiiiHVdn0yEEB5JkqAsS5BpmqhpmiiK4l7yO5Zloa5rCCw5joOL78C6MrjxCv9kZGt+l69Nk33fqeM40DSNC39x+wXkef55cm3bouu67429gk1mGAaPi0cOfABiBGuWj0KUbQAAAABJRU5ErkJggg=='));
		}
	}
	
	// I'm not sure this function is really needed...
	function ls($path, $show_folders = false, $show_hidden = false) {
		global $_self, $_total, $_total_size;
		$ls = array();
		$ls_d = array();
		if (($dh = @opendir($path)) === false) return $ls;
		if (substr($path, -1) != '/') $path .= '/';
		while (($file = readdir($dh)) !== false) {
			if ($file == $_self) continue;
			if ($file == '.' || $file == '..') continue;
			if (!$show_hidden) if (substr($file, 0, 1) == '.') continue;
			$isdir = is_dir($path . $file);
			if (!$show_folders && $isdir) continue;
                        $testA = mb_decode_mimeheader($file);
			$testA = preg_replace('/^(\'(.*)\'|"(.*)")$/', '$2$3', $testA);
			$item = array('testA' => $testA, 'name' => $file, 'isdir' => $isdir, 'size' => $isdir ? 0 : filesize($path . $file), 'time' => filemtime($path . $file));
			if ($isdir) $ls_d[] = $item; else $ls[] = $item;
			$_total++;
			$_total_size += $item['size'];
		}
		return array_merge($ls_d, $ls);
	}
	
	// Get the list of files
	$items = ls('.' . (empty($_browse) ? '' : '/' . $_browse), $showDirectories, $showHiddenFiles);
	
	// Sort it
	function sortByName($a, $b) { global $showDirectoriesFirst; return ($a['isdir'] == $b['isdir'] || !$showDirectoriesFirst ? strtolower($a['name']) > strtolower($b['name']) : $a['isdir'] < $b['isdir']); }
	function sortBySize($a, $b) { return ($a['isdir'] == $b['isdir'] ? $a['size'] > $b['size'] : $a['isdir'] < $b['isdir']); }
	function sortByTime($a, $b) { return ($a['time'] > $b['time']); }
	switch (@$_GET['s']) {
		case 'size': $_sort = 'size'; usort($items, 'sortBySize'); break;
		case 'time': $_sort = 'time'; usort($items, 'sortByTime'); break;
		default    : $_sort = 'name'; usort($items, 'sortByName'); break;
	}
	
	// Reverse?
	$_sort_reverse = (@$_GET['r'] == '1');
	if ($_sort_reverse) $items = array_reverse($items);
	
	// Add parent
	if ($showParent && $_path != '/' && empty($_browse)) array_unshift($items, array(
		'name' => '..',
		'isparent' => true,
		'isdir' => true,
		'size' => 0,
		'time' => 0
	));

	// Add parent in case of browsing a sub-folder
	if (!empty($_browse)) array_unshift($items, array(
		'name' => '..',
		'isparent' => false,
		'isdir' => true,
		'size' => 0,
		'time' => 0
	));
	
	// 37.6 MB is better than 39487001
	function humanizeFilesize($val, $round = 0) {
		$unit = array('','K','M','G','T','P','E','Z','Y');
		do { $val /= 1024; array_shift($unit); } while ($val >= 1000);
		return sprintf('%.'.intval($round).'f', $val) . ' ' . array_shift($unit) . 'B';
	}
	
	// Titles parser
	function getTitleHTML($title, $breadcrumbs = false) {
		global $_path, $_browse, $_total, $_total_size, $sizeDecimals;
		$title = htmlentities(str_replace(array('{{files}}', '{{size}}'), array($_total, humanizeFilesize($_total_size, $sizeDecimals)), $title));
		$path = htmlentities($_path);
		if ($breadcrumbs) $path = sprintf('<a href="%s">%s</a>', htmlentities(buildLink(array('b' => ''))), $path);
		if (!empty($_browse)) {
			if ($_path != '/') $path .= '/';
			$browseArray = explode('/', trim($_browse, '/'));
			foreach ($browseArray as $i => $part) {
				if ($breadcrumbs) {
					$path .= sprintf('<a href="%s">%s</a>', htmlentities(buildLink(array('b' => implode('/', array_slice($browseArray, 0, $i + 1))))), htmlentities($part));
				} else {
					$path .= htmlentities($part);
				}
				if (count($browseArray) > ($i + 1)) $path .= '/';
			}
		}
		return str_replace('{{path}}', $path, $title);
	}
	
	// Link builder
	function buildLink($changes) {
		global $_self;
		$params = $_GET;
		foreach ($changes as $k => $v) if (is_null($v)) unset($params[$k]); else $params[$k] = $v;
		foreach ($params as $k => $v) $params[$k] = urlencode($k) . '=' . urlencode($v);
		return empty($params) ? $_self : $_self . '?' . implode('&', $params);
	}

?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	
	<meta charset="UTF-8">
	
	<title><?php echo getTitleHTML($title) ?></title>
	
	<style type="text/css">
		
		* {
			margin: 0;
			padding: 0;
			border: none;
		}
		.responsive {
 			max-width: 100%;
  			height: auto;
		}
		body {
			text-align: center;
			font-family: sans-serif;
			font-size: 1.35vw;
			color: #000000;
		}
		
		#wrapper {
			max-width: 100%;
			*width: 100%;
			margin: 0 auto;
			text-align: left;
		}
		
		body#left {
			text-align: left;
		}
		
		body#left #wrapper {
			margin: 0 10px;
		}
  		br {
        		line-height: 115%;
     		}
	
		start {
			font-size: 2.45vw;
			padding: 0 20px;
			margin: 20px 0 0;
			font-weight: bold;

		}
		h {
			font-size: 1.35vw;
			padding: 0 20px;
			margin: 10px 0 0;
			color: #666666;
			font-weight: normal;
		}
		h2 {
			font-size: 1.35vw;
			padding: 0 40px;
			margin: 10px 0 0;
			color: #666666;
			font-weight: normal;
		}
		
		a {
			color: #003399;
			text-decoration: none;
		}
		
		a:hover {
			color: #0066cc;
			text-decoration: underline;
		}
		
		ul#header {	
			margin-top: 20px;
		}
		
		ul li {
			display: block;
			list-style-type: none;
			overflow: hidden;
			padding: 10px;
		}
		
		ul li:hover {
			background-color: #f3f3f3;
		}
		
		ul li .date {
			text-align: center;
			width: 120px;
		}
		
		ul li .size {
			text-align: right;
			width: 90px;
		}
		
		ul li .date, ul li .size {
			float: right;
			font-size: 1.35vw;
			display: block;
			color: #666666;
		}
		
		ul#header li {
			font-size: 1.35vw;
			font-weight: bold;
			border-bottom: 1px solid #cccccc;
		}
		
		ul#header li:hover {
			background-color: transparent;
		}
		
		ul#header li * {
			color: #000000;
			font-size: 1.35vw;
		}
		
		ul#header li a:hover {
			color: #666666;
		}
		
		ul#header li .asc span, ul#header li .desc span {
			padding-right: 15px;
			background-position: right center;
			background-repeat: no-repeat;
		}
		
		ul#header li .asc span {
			background-image: url('<?php echo $_self ?>?i=asc');
		}
		
		ul#header li .desc span {
			background-image: url('<?php echo $_self ?>?i=desc');
		}
		
		ul li.item {
			border-top: 1px solid #f3f3f3;
		}
		
		ul li.item:first-child {
			border-top: none;
		}
		
		ul li.item .name {
			font-weight: normal;
		}
		
		ul li.item .directory, ul li.item .file {
			padding-left: 20px;
			background-position: left center;
			background-repeat: no-repeat;
		}
		
		ul li.item .directory {
			background-image: url('<?php echo $_self ?>?i=directory');
		}
		
		ul li.item .file {
			background-image: url('<?php echo $_self ?>?i=file');
		}
		authors{
                        font-size: 1.7vw;
                }		
		#footer {
			color: #cccccc;
			font-size: 1.35vw;
			margin-top: 40px;
			margin-bottom: 20px;
			padding: 0 10px;
			text-align: left;
		}
		
		#footer a {
			color: #cccccc;
			font-weight: bold;
		}
		
		#footer a:hover {
			color: #999999;
		}
		
	</style>
	
</head>
<body <?php if ($alignment == 'left') echo 'id="left"' ?>>

	<div id="wrapper">
		<div style="width: 26%; float:left">
		<img src="/files/star.png" class="responsive"><br></img><br><start><?php echo getTitleHTML($title, $breadcrumbs) ?></start><br><br><h>Jun 2003 - <?php
echo date("M d Y")?></h><br><h><?php echo getTitleHTML($subtitle, $breadcrumbs) ?></h><br><br><h>[<a href="search.php">search</a>]</h><br><br><h>Source:<h><br><h2><a href="https://github.com/powercrypt/Usenet-Archiving-Tool/">GitHub</a></h2><br><h>News Clients:</h><h2><a href="https://pan.rebelbase.com/">Pan</a></h2><br><h>Servers:</h><br><h2><a href="https://groups.google.com/g/alt.magick">Google Groups</a></h2><h2><a href="https://www.eternal-september.org">Eternal-September</a></h2><br><h>Hosted on:</h><br><h2><a href="https://www.d-elete.com">D-Elete</a></h2><br><h>Contact:</h><br><h2><a href="mailto:realityhacker@gmail.com">realityhacker@gmail.com</a></h2><br></div>
<div style="width: 74%; float:right">	
		<ul id="header">
			<li>
				<a href="<?php echo buildLink(array('s' => 'size', 'r' => (!$_sort_reverse && $_sort == 'size') ? '1' : null)) ?>" class="size <?php if ($_sort == 'size') echo $_sort_reverse ? 'desc' : 'asc' ?>"><span style="font-size: 1.6vw;">Size</span></a>
				<a href="<?php echo buildLink(array('s' =>  null , 'r' => (!$_sort_reverse && $_sort == 'name') ? '1' : null)) ?>" class="name <?php if ($_sort == 'name') echo $_sort_reverse ? 'desc' : 'asc' ?>"><span style="font-size: 1.6vw;">Users</span></a>
			</li>
			
		</ul>
		<br>
		<ul>
			
			<?php foreach ($items as $item): ?>
				
				<li class="item">
				
					<span class="size"><?php echo $item['isdir'] ? '-' : humanizeFilesize($item['size'], $sizeDecimals) ?></span>
					
					
					<?php
						if ($item['isdir'] && $browseDirectories && !@$item['isparent']) {
							if ($item['name'] == '..') {
								$itemURL = buildLink(array('b' => substr($_browse, 0, strrpos($_browse, '/'))));
							} else {
								$itemURL = buildLink(array('b' => (empty($_browse) ? '' : (string)$_browse . '/') . $item['name']));
							}
						} else {
							$itemURL = (empty($_browse) ? '' : str_replace(['%2F', '%2f'], '/', rawurlencode((string)$_browse)) . '/') . rawurlencode($item['name']);
						}
					?>
					
					<a href="<?php echo htmlentities($itemURL) ?>" class="name <?php if ($showIcons) echo $item['isdir'] ? 'directory' : 'file' ?>"><authors><?php echo htmlentities($item['testA']) . ($item['isdir'] ? ' /' : '') ?></authors></a>
					
				</li>
				
			<?php endforeach; ?>
			
		</ul>
		<?php if ($showFooter): ?>
			
			<p id="footer">
				Powered by <a href="https://github.com/lorenzos/Minixed" target="_blank">Minixed</a>, a PHP directory indexer
			</p>
			
		<?php endif; ?>
</div>		
	</div>
	
</body>
</html>
