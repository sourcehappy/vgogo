<?php
/**
 * �ٲ����Ĳ�Ʒ�б�
 * 
 * @author Balsampear
 * @since 2013-4-23
 */

//�������ļ��ģ�����Ҫ�ṩһ��$prodList����

foreach ($prodList as $prod) {
	?>
			 <div class='mode'>
			  <p class='pic'>
			   <a href='/item.php?id=<?=$prod['prod_id']?>'><img alt="<?=$prod['title']?>" src="<?=$prod['main_img'].'_310x310.jpg'?>" /></a>
			  </p>
				<h3 class='tit'><span style="font-size: 12px; color:#333;">&nbsp;&nbsp;��<?=$prod['price']?>&nbsp;&nbsp;&nbsp;
				<a href='/list.php?cid=<?=$prod['class_id']?>' style="font-size: 12px; color:#333;"><?=ClassData::getName($prod['class_id'], true)?></a></span></h3>
			 </div>
<?php 
	}