<?php
/**
 * ��ҳ
 * 
 * @author Balsampear
 * @since 2013-4-14
 * QQȺ: 199572084
 */

require('common/global.inc.php');


//��ҳ�Ƽ���Ʒ����
$recommendList = Product::getListByTag(false, 0, 3, 37);
//��ҳ�ٲ����Ĳ�Ʒ����
$prodList = Product::getList(false, 0, 50);
//��ҳ����������
$artList = Article::getList(2, 4);

// var_dump($prodList);
?>

<div  style="width:1020px;
	margin-left:auto;
	margin-right:auto;
	background-color:#FFFFFF;
	height:auto;">
  <!--�����-->
  <div id="vgogoleft">
    <!--logo-->
    <div id="vgogologo"><img src="/assets/images/logo.png"  /></div>
    <!--/logo-->
    <!--����ǩ-->
<?php 
	include 'inc/leftNav.inc.php';
?>
  <!--/����ǩ-->
  </div>
  <!--/�����-->
  <!--�Ҳ����-->
  <div id="vgogoright">
<?php 
	include 'inc/topNav.inc.php';
?>
    <!--����ͼƬ-->
    <div id="vgogoindexturn">
      <div style="position: relative; left:40px; top:53px;width:350px">
      	<div class="banner">
	        <div class="banner_bg" style="opacity: 0.3;"></div>
	        <a href="http://vgogo.uz.taobao.com/#" class="banner_info"></a> 
	        <ul class="list"></ul>
	        <div class="banner_list"> 
<?php 
	
	foreach ($recommendList as $row) {
?>
      	<a target="_blank" class="pic" href="/view/front/item.php?id=<?=$row["prod_id"]?>">
      		<img src="<?=$row['main_img']?>_250x250.jpg" width="250" title="<?=$row['title']?>" alt="<?=$row['title']?>" />
      	</a>
<?php
	}

	
?>		</div>
      </div>
      </div>
    </div>
    <div id="clearboth"></div>
    <!--/����ͼƬ��-->
    <!--ͼ����Ѷ-->
    <div id="vgogonews">
      <div id="vgogonewsleft">
        <div id="vgogonewsleftpic">
        	<a href="<?=$artList[0]['jump_url'] ? $artList[0]['jump_url'] : '/art.php?id='.$artList[0]['art_id']?>">
        		<img src="<?=$artList[0]['main_img']?>" width="100" />
        	</a>
        </div>
        <div id="vgogonewsleftnews">
			<?php 
                foreach ($artList as $row) {
                    echo '<div><a href="'.($row['jump_url'] ? $row['jump_url'] :'/art.php?id='.$row['art_id']).'">'.$row['title'].'</a></div>';
                }
            ?>
        </div>
      </div>
      <div id="vgogonewsright">
<?php 
	foreach ($artList as $i => $row) {
		if ($i > 0)	echo '<a href="'.($row['jump_url'] ? $row['jump_url'] :'/art.php?id='.$row['art_id']).'"><img src="'.$row['main_img'].'_100x100.jpg" /></a>';
	}
?>
      </div>
    </div>
    <div id="clearboth"></div>
    <!--/ͼ����Ѷ-->
    <!--�����ϼܱ���-->
    <div id="indexnewsitem">
       <div id="indexnewsitemtitle">�����ϼ���Ʒ</div>
       <div id="indexnewsitemlist">
			<div class="wrap" name="wrap active">
<?php 
	include 'inc/prodlist.inc.php';
?>
			</div>
       </div>
    </div>
    <!--/�����ϼܱ���-->
    <div id="clearboth"></div>
  </div>
  <!--/�Ҳ���-->
<?php 
	include 'inc/rightNav.inc.php';
?>
  <div id="clearboth"></div>
</div>

<script src="/assets/javascripts/switch.js"></script>
