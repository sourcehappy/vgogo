<?php
/**
 * ��ϸҳ
 * 
 * @author Balsampear
 * @since 2013-4-21
 * QQȺ: 199572084
 */
require('common/global.inc.php');

$id = intval($_GET['id']);

$artData = array();
if ($id > 0) {
	$artData = Article::get($id);
}

//���ķ�������
$classList = ClassData::getList(false, $artData['class_id']);
// $classList = ClassData::getList(false, 1);



// var_dump($artData);
?>
<div style="width:1020px; margin-left:auto; margin-right:auto;">
  <!--���-->
  <div style=" width:930px; float:left;">
      <div style=" width:208px; float:left;">
      	<div  style="border-right-width: 1px;
		border-bottom-width: 1px;
		border-top-style: none;
		border-right-style: solid;
		border-bottom-style: solid;
		border-left-style: none;
		border-right-color: #eeeeee;
		border-bottom-color: #eeeeee;"><img src="/assets/images/logo.png" /></div>
<?php 
	foreach ($classList as $row) {
	?>
    <div id="vgogolefttag"> ��<?php echo $row['name'];?> </div>
<?php 
		$clist = ClassData::getList($row['class_id']);
		echo '<div id="vgogolefttag2">';
		foreach ($clist as $row) {
    		echo '<a href="/list.php?cid='.$row['class_id'].'" target="_blank">'.$row['name'].'</a>';
		}
		echo '</div>';
	}
?>
      </div>
      <div style=" width:680px; float:left; margin-left:22px;">
<?php 
	include 'inc/topNav.inc.php';
?>
       </div>
      <div style="clear:both;"></div>
      <div>
      ��ǰλ�ã�<?=$artData['title']?> 
      <hr>
      <?=$artData['text']?>
      </div>
  </div>
  <!--/���-->
    <!--�Ҳม��-->
  <div style=" width:90px; float:left;">
<?php 
	include 'inc/rightNav.inc.php';
?>
  </div>
    <!--/�Ҳม��-->
</div>
