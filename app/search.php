<?php
/**
 * 专题页
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

$key = trim($_GET['key']);
$order = intval($_GET['order']);
$order = $order ? $order : 1;

$url = '/search.php?key='.urlencode($key);

//瀑布流的产品数据
$rec = true;
if ($pno == 1) {
	$rec = $recCount > 0 ? false : true;
}
$prodList = Product::getList($rec, ($pno - 1) * PER_PAGE_RECORD, PER_PAGE_RECORD, '', $key);
if ( ! is_bool($rec)) $recCount = $rec;
$jumpURL = $url.'&rec='.$recCount.'&pno=';
$pageCount = intval($recCount / PER_PAGE_RECORD) + ($recCount % PER_PAGE_RECORD > 0 ? 1 : 0);
// echo $recCount.'-'.$pageCount;




// var_dump($prodList);
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
    <!--/列表页宝贝--> <div style="clear:both;"></div>
  </div>
  <!--/右侧框架-->
<?php 
	include 'inc/rightNav.inc.php';
?>
  <div style="clear:both;"></div>
</div>

<script src="/assets/javascripts/switch.js"></script>
