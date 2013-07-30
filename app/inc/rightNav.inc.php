<?php
/**
 * ÓÒ²àÆ¯¸¡
 * 
 * @author Balsampear
 * @since 2013-4-22
 */


$visitList = Product::getList(false, 0, 5, Visit::getProdList(6, $id));
?>
  <!--Ðü¸¡¿ò¼Ü-->
  <div style=" float:left;
 width:90px;">
    <div style="background-color:#000000;
 width:85px;
 padding-left:5px;
 height:auto;
 position:fixed;
 display: table-cell;
 vertical-align: middle;
"><img src="/assets/images/quick_tit.gif" border="0" />
<?php 
	foreach ($visitList as $pd) {
		echo '<a href="/item.php?id='.$pd['prod_id'].'"><img src="'.$pd['main_img'].'_150x150.jpg"  width="80"/></a>';
	}
?><a href="#vgogotop"><img src="/assets/images/quick_top.gif"  border="0"/></a>
  </div>
  </div>
  <!--/Ðü¸¡¿ò¼Ü-->