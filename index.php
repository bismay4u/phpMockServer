<?php
// Main file for delivering the mock data to user
//
// @author Bismay [bismay@smartinfologiks.com]
//ACL,BIND,CHECKOUT,CONNECT,COPY,DELETE,GET,HEAD,LINK,LOCK,M-SEARCH,MERGE,MKACTIVITY,MKCALENDAR,MKCOL,MOVE,NOTIFY,PATCH,POST,PROPFIND,PROPPATCH,PURGE,PUT,REBIND,REPORT,SEARCH,SUBSCRIBE,TRACE,UNBIND,UNLINK,UNLOCK,UNSUBSCRIBE
$method = $_SERVER['REQUEST_METHOD'];
$uri = current(explode("?", $_SERVER['REQUEST_URI']));

if(strlen($uri)<=0) {
	echo "<h1 align=center>MOCK Server</h1>";
	exit();
}

if(isset($_GET['format']) && strlen($_GET['format'])>0) {
	$srcFiles = [
		__DIR__."/mockData{$uri}.{$_GET['format']}",
	];
} else {
	$srcFiles = [
		__DIR__."/mockData{$uri}.json",
		__DIR__."/mockData{$uri}.xml",
		__DIR__."/mockData{$uri}.txt",
		__DIR__."/mockData{$uri}.html",
	];
}

$srcFile = false;
$srcExt = "";
foreach($srcFiles as $f) {
	if(file_exists($f)) {
		$srcFile = $f;
		$srcExt = explode(".", $srcFile);
		$srcExt = strtolower(end($srcExt));
		break;
	}
}

if($srcFile) {
	//ob_flush();
	//header('Content-Disposition: attachment; filename="downloaded.pdf"');
	header('Content-Type: '.getMimeHeader($srcExt));
	//header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	//header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	readfile($srcFile);
} else {
	http_response_code(404);
	echo "Mock API Not Found";
}

function getMimeHeader($ext) {
	switch(strtolower($ext)) {
		case "json":
			return "application/json";
			break;
		case "xml":
			return "application/xml";
			break;
		case "text":
		case "txt":
			return "text/plain";
			break;
		case "html":
			return "text/html";
			break;
	}
	
	return "application/text";
}
?>