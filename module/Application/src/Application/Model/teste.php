<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>jQuery.noConflict demo</title>
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>
<body>
 
<div id="log">
  <h3>Before $.noConflict(true)</h3>
</div>
<script src="https://code.jquery.com/jquery-1.6.2.js"></script>
 
<script>
var $log = $( "#log" );
 
$log.append( "2nd loaded jQuery version ($): " + $.fn.jquery + "<br>" );
 
// Restore globally scoped jQuery variables to the first version loaded
// (the newer version)
 
jq162 = jQuery.noConflict( true );
 
$log.append( "<h3>After $.noConflict(true)</h3>" );
$log.append( "1st loaded jQuery version ($): " + $.fn.jquery + "<br>" );
$log.append( "2nd loaded jQuery version (jq162): " + jq162.fn.jquery + "<br>" );
</script>
 
</body>
</html>