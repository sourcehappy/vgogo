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

$pagePos = '';
$item = array();
$tagList = array();

if ($id > 0) {
	$item = Product::get($id);
	if ($item) {
		$url = '/list.php?cid='.$item['class_id'];
		$pagePos = ClassData::getName($item['class_id']);
		$tagList = Tag::getListByProd($id);
		
		//�������ͷ��ʼ�¼
		if (Visit::add($id)) {
			if (Visit::checkTodayExist($id)) {
				Product::addClickNum($id);
			}
		}
	}
}



// var_dump($visitList);
// var_dump($item);
?>
<div style="width:1020px;
	margin-left:auto;
	margin-right:auto;
	background-color:#FFFFFF;
	height:auto;">
  <!--�����-->
  <div  style="float:left;
width:208px;
margin-right:22px;
overflow:hidden;">
    <!--logo-->
    <div  style="border-right-width: 1px;
	border-bottom-width: 1px;
	border-top-style: none;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: none;
	border-right-color: #eeeeee;
	border-bottom-color: #eeeeee;"><img src="images/logo.png" /></div>
    <!--/logo-->
    <!--����ǩ-->
<?php 
	include 'inc/leftNav.inc.php';
?>   
  </div>
  <!--/�����-->
  <!--�Ҳ����-->
  <div style="float:left;
width:680px;
margin-right:20px;">
<?php 
	include 'inc/topNav.inc.php';
?>
    <!--����ͼƬ-->
    <div style="clear:both;"></div>
    <!--/����ͼƬ��-->
    <!--�б�ҳ����-->
    <div id="indexnewsitem">
      <div id="indexnewsitemtitle">��ǰλ�ã�<?=$pagePos?> > <?=$item['title']?></div>
      <div  style="
	width:680px;
	
	font-size: 14px;

	border-bottom-width: 1px;

	border-bottom-style: solid;

	border-bottom-color: #cccccc;">
        <!--ͼƬ+����+����+����-->
        <div style="width:300px; height:300px; float:left; ">
        	<img src="<?=$item['main_img']?>_300x300.jpg" />
        </div>
        <div style="width:360px; height:300px; margin-left:10px; float:left">
		<!--��ͼ�Ҳ��������-->
			 	<div style="width:360px; margin-top:20px; margin-bottom:20px; overflow:hidden;"><img src="http://img02.taobaocdn.com/imgextra/i2/185618059/T2ZZ9_XoxaXXXXXXXX_!!185618059.png" /></div>
				<div style="width:340px; margin-top:10px; margin-bottom:30px; padding-left:20px;">
					<!--ȥ����+����-->
					<div style="float:left;">
						<a href="<?=Html::mkItemURL($item['item_id'])?>" target="_blank">
							<img src="http://img01.taobaocdn.com/imgextra/i1/185618059/T2rQfgXX4bXXXXXXXX_!!185618059.gif" />
						</a>
					</div>
					<div style="float:left; margin-left:10px; margin-top:4px; font-size:12px;">��ϵ�ƹ�:<br><?=Html::mkWangwangCode($item['wangwang'])?></div>
					<div style="clear:both;"></div>
					<!--/ȥ����+����-->
				
				</div>
				 <div  style="height:30px;
							width:360px;
							line-height:30px;
							font-size: 14px;
							border-bottom-width: 1px;
							border-bottom-style: solid;
						    border-bottom-color: #cccccc;
							border-top-width: 1px;
							border-top-style: solid;
						    border-top-color: #cccccc;"> 
<?php 
	foreach ($tagList as $tag) {
		echo '<a href="'.$url.'&tid='.$tag['tag_id'].'">'.$tag['name'].'</a>, ';
	}
?>
				 </div>
				 
		
		<!--/��ͼ�Ҳ��������-->
		
		</div>
        <!--/ͼƬ+����+����+����-->
		<div style="clear:both;"></div>
      </div>
    
      <div style="clear:both;"></div>
      <div id="indexnewsitemlist">
        <div id="vgogoitembody">
          <!--����-->
          <?=$item['memo']?>
          <!--/����-->
        </div>
      </div>
    </div>
    <!--/�б�ҳ����-->
    <div style="clear:both;"></div>
  </div>
  <!--/�Ҳ���-->
<?php 
	include 'inc/rightNav.inc.php';
?>
  <div style="clear:both;"></div>
</div>

