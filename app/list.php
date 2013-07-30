<?php
/**
 * 列表页
 * 
 * @author Balsampear
 * @since 2013-4-21
 * QQ群: 199572084
 */
require('common/global.inc.php');

define('PER_PAGE_RECORD', 20);//每页的条数
$pno = intval($_GET['pno']);//页码
$recCount = intval($_GET['rec']);//总条数
$pno = $pno > 0 ? $pno : 1;

$tagId = intval($_GET['tid']);
$delTagId = intval($_GET['deltid']);
$tagIds = trim($_GET['tids']);
$classId = intval($_GET['cid']);//分类ID
$order = intval($_GET['order']);//排序
$classId = $classId ? $classId : 2;
$order = $order ? $order : 1;

$url = '/list.php?cid=' . $classId;

//关于tag_id的拼接
if ($tagIds) {
	$tagIds = explode(',', $tagIds);
}else{
	$tagIds = array();
}
//有要增加的tag_id
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
//有要删除的tag_id
if ($delTagId > 0) {
	$k = array_search($delTagId, $tagIds);
	if ($k !== false) {
		unset($tagIds[$k]);
	}
}
//最后整合URL，并取出造中的TAG相关数据
$selectTagList = array();
if ($tagIds) {
	$tids = implode(',', $tagIds);
	$url .= '&tids='.urlencode($tids);
	$selectTagList = Tag::getList($tids);
}



//标签列表
$tagList = Tag::getListByClass($classId);

$rec = true;
if ($pno == 1) {
	$rec = $recCount > 0 ? false : true;
}
$prodList = Product::getListByClassOrTag($rec, ($pno - 1) * PER_PAGE_RECORD, PER_PAGE_RECORD, $classId, $tagIds, $order);
if ( ! is_bool($rec)) $recCount = $rec;
$jumpURL = $url.'&rec='.$recCount.'&pno=';
$pageCount = intval($recCount / PER_PAGE_RECORD) + ($recCount % PER_PAGE_RECORD > 0 ? 1 : 0);
// echo $recCount.'-'.$pageCount;

$className = $classId ? ClassData::getName($classId) : '';


//更新分类点击数
ClassData::addClickNum($classId);

// var_dump($prodList);
// var_dump($tagList);
// var_dump($tagIds);
// var_dump($selectTagList);
// var_dump($visitList);
?>

<div style="width:1020px;
	margin-left:auto;
	margin-right:auto;
	background-color:#FFFFFF;
	height:auto;">
  <!--左侧框架-->
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
    <!--左侧标签-->
<?php 
	include 'inc/leftNav.inc.php';
?>
  </div>
  <!--/左侧框架-->
  <!--右侧侧框架-->
  <div style="float:left;
width:680px;
margin-right:20px;">
<?php
	include 'inc/topNav.inc.php';
?>
    <!--焦点图片-->
    <div style="clear:both;"></div>
    <!--/焦点图片区-->
   
    <!--列表页宝贝-->
    <div id="indexnewsitem">
      <div id="indexnewsitemtitle">当前位置：
<?php 
	echo $className.' | ';
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
			echo $tag['tpname'] . '：';
		}elseif ($tpid != $tag['tagpr_id']) {
			$tpid = $tag['tagpr_id'];
?>
      </div>
      <div   id="vgogolisttag"> 
<?php
			echo $tag['tpname'] . '：';
		}
		
		echo '<a href="'.$url.'&tid='.$tag['tag_id'].'">' . $tag['name'] . '</a> ';
 		
	}
?>
      </div>
      
<?php 
	include 'inc/order.inc.php';
?>
      
        <div id="indexnewsitemlist">
        <div style="width:680px; ">
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
    <!--/列表页宝贝--> <div  style="clear:both;"></div>
  </div>
  <!--/右侧框架-->
<?php 
	include 'inc/rightNav.inc.php';
?><div style="clear:both;"></div>
</div>

<script src="/assets/javascripts/switch.js"></script>
