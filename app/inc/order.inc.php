<?php
/**
 * ��ҳ��
 * 
 * @author Balsampear
 * @since 2013-4-23
 */

?>
       <div  id="vgogolisttag"> ����
<?php 
	if ($order == 1) {
		echo '����  <a href="'.$url.'&order=2">����</a>';
	}else{
		echo '<a href="'.$url.'&order=1">����</a>  ����';
	}
?>
       </div>