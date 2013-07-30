<?php
/**
 * 翻页
 * 
 * @author Balsampear
 * @since 2013-4-23
 */

if ($pno > 1) {
	echo '<div id="indexnewsitemtitle"><a href="'.$jumpURL.($pno-1).'">上一页</a></div>';
}
echo ' ';
if ($pageCount > $pno) {
	echo '<div id="indexnewsitemtitle"><a href="'.$jumpURL.($pno+1).'">下一页</a></div>';
}
?>
