<?php

require_once MAX_DEV.'/lib/xajax/xajax.inc.php';

$xajax = new xajax();
//$xajax->debugOn(); // Uncomment this line to turn debugging on
$xajax->debugOff(); // Uncomment this line to turn debugging on
$xajax->registerFunction("testAjax");
$xajax->registerFunction("editFieldProperty");
$xajax->registerFunction("selectDataDictionary");
// Process any requests.  Because our requestURI is the same as our html page,
// this must be called before any headers or HTML output have been sent
$xajax->processRequests();

$jsfile = getcwd().'/schema.js';
if (!file_exists($jsfile))
{
    ob_start();
    $xajax->printJavascript('../lib/xajax/'); // output the xajax javascript. This must be called between the head tags
    $js = ob_get_contents();
    ob_end_clean();

    $pattern    = '/(<script type="text\/javascript">)(?P<jscript>[\w\W\s]+)(<\/script>)/U';
	if (preg_match($pattern, $js, $aMatch))
	{
        $js = $aMatch['jscript'];
	}
	else
	{
        echo "Error parsing javascript generated by xAjax.  You should check the {$jsfile} manually.";
	}

    $fp = fopen($jsfile, 'w');
    if ($fp === false) {
        echo "Error opening output file {$jsfile} for writing.  Check permissions.";
        die();
    }
    else
    {
        fwrite($fp, $js);
        fclose($fp);
    }
}


?>