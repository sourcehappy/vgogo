<?php
/**
 * ר��ҳ
 * 
 * @author Balsampear
 * @since 2013-4-21
 * QQȺ: 199572084
 */
require('common/global.inc.php');

define('PER_PAGE_RECORD', 20);//ÿҳ������
$pno = intval($_GET['pno']);//ҳ��
$recCount = intval($_GET['rec']);//������
$pno = $pno > 0 ? $pno : 1;

$tagId = intval($_GET['tid']);
$tagprId = intval($_GET['tpid']);
$delTagId = intval($_GET['deltid']);
$tagIds = trim($_GET['tids']);
$order = intval($_GET['order']);
$order = $order ? $order : 1;

$url = '/topic.php?tpid=' . $tagprId;

//��ǩ�б�
$tagList = Tag::getListByProperty($tagprId);

//����tag_id��ƴ��
if ($tagIds) {
	$tagIds = explode(',', $tagIds);
}else{
	$tagIds = array();
	//var_dump($list);
	foreach ($tagList as $tag) {
		$tagIds[] = $tag['tag_id'];
	}
}
//��Ҫ���ӵ�tag_id
if ($tagId > 0) {
	Tag::addClickNum($tagId);
	if (array_search($tagId, $tagIds) === false) {
		$tagIds[] = $tagId;
	}
	foreach ($tagIds as $k => $v) {
		if (! $v) {
			unset($tagIds[$k]);
		}else{
			$tagIds[$k] = intval($v);
		}
	}
}
//��Ҫɾ����tag_id
if ($delTagId > 0) {
	$k = array_search($delTagId, $tagIds);
	if ($k !== false) {
		unset($tagIds[$k]);
	}
}
//�������URL����ȡ�����е�TAG�������
$selectTagList = array();
if ($tagIds) {
	$tids = implode(',', $tagIds);
	$url .= '&tids='.urlencode($tids);
	$selectTagList = Tag::getList($tids);
}



//��ҳ�ٲ����Ĳ�Ʒ����
$rec = true;
if ($pno == 1) {
	$rec = $recCount > 0 ? false : true;
}
$prodList = Product::getListByTag($rec, ($pno - 1) * PER_PAGE_RECORD, PER_PAGE_RECORD, $tagIds);
if ( ! is_bool($rec)) $recCount = $rec;
$jumpURL = $url.'&rec='.$recCount.'&pno=';
$pageCount = intval($recCount / PER_PAGE_RECORD) + ($recCount % PER_PAGE_RECORD > 0 ? 1 : 0);
// echo $recCount.'-'.$pageCount;




// var_dump($prodList);
// var_dump($tagList);
// var_dump($tagIds);
// var_dump($visitList);
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
     <div id="indexnewsitemtitle">��ǰλ�ã�
<?php 
	
	foreach ($selectTagList as $tag) {
		echo '<a href="'.$url.'&deltid='.$tag['tag_id'].'">' . $tag['name'] . '<img src="/assets/images/close.jpg" border="0" width="15" /></a> ';
	}
?>
      </div>
      <div  id="vgogolisttag"> 
<?php 
	$tpid = 0;
	foreach ($tagList as $tag) {
		if ($tpid == 0) {
			$tpid = $tag['tagpr_id'];
			echo $tag['tpname'] . '��';
		}elseif ($tpid != $tag['tagpr_id']) {
			$tpid = $tag['tagpr_id'];
?>
      </div>
      <div   id="vgogolisttag"> 
<?php
			echo $tag['tpname'] . '��';
		}
		
		echo '<a href="'.$url.'&tid='.$tag['tag_id'].'">' . $tag['name'] . '</a> ';
 		
	}
?>
      </div>
<?php 
	include 'inc/order.inc.php';
?>
      
        <div id="indexnewsitemlist">
        <div style="width:680px;">
          <div class="wrap" name="wrap active">
<?php 
	include 'inc/prodlist.inc.php';
?>
		</div>
<?php 
	include 'inc/page.inc.php';
?>
      </div>
      </div>
    </div>
    <!--/�б�ҳ����--> <div style="clear:both;"></div>
  </div>
  <!--/�Ҳ���-->
<?php 
	include 'inc/rightNav.inc.php';
?>
	<div style="clear:both;"></div>
</div>

<script src="/assets/javascripts/switch.js"></script>
