<?php
/**
 * 分页用
 * 
 * @author Balsampear
 * @since 2013-4-23
 */

?>
       <div  id="vgogolisttag"> 排序：
<?php 
	if ($order == 1) {
		echo '最新  <a href="'.$url.'&order=2">最热</a>';
	}else{
		echo '<a href="'.$url.'&order=1">最新</a>  最热';
	}
?>
       </div>