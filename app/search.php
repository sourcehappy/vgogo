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

$key = trim($_GET['key']);
$order = intval($_GET['order']);
$order = $order ? $order : 1;

$url = '/search.php?key='.urlencode($key);

//�ٲ����Ĳ�Ʒ����
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
