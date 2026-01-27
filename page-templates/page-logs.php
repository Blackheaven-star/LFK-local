<?php /* Template Name: Logs Page Template */ ?>

<?php $webActivityArr = l4k_getActivityLog(); ?>
<pre><?php print_r($webActivityArr); ?></pre>

<?php 
/*
header('Content-Type: application/json; charset=utf-8');
$webActivityArr = l4k_getActivityLog();
echo json_encode($webActivityArr, JSON_PRETTY_PRINT);
exit;
*/
?>