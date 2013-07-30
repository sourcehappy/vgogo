<?php
/**
 * 首页
 * 
 * @author Balsampear
 * @since 2013-4-14
 * QQ群: 199572084
 */

require('common/global.inc.php');


//首页推荐产品数据
$recommendList = Product::getListByTag(false, 0, 3, 37);
//首页瀑布流的产品数据
$prodList = Product::getList(false, 0, 50);
//首页的文章数据
$artList = Article::getList(2, 4);

// var_dump($prodList);
?>

<div  style="width:1020px;
	margin-left:auto;
	margin-right:auto;
	background-color:#FFFFFF;
	height:auto;">
  <!--左侧框架-->
  <div id="vgogoleft">
    <!--logo-->
    <div id="vgogologo"><img src="/assets/images/logo.png"  /></div>
    <!--/logo-->
    <!--左侧标签-->
<?php 
	include 'inc/leftNav.inc.php';
?>
  <!--/左侧标签-->
  </div>
  <!--/左侧框架-->
  <!--右侧侧框架-->
  <div id="vgogoright">
<?php 
	include 'inc/topNav.inc.php';
?>
    <!--焦点图片-->
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
    <!--/焦点图片区-->
    <!--图文资讯-->
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
    <!--/图文资讯-->
    <!--最新上架宝贝-->
    <div id="indexnewsitem">
       <div id="indexnewsitemtitle">最新上架商品</div>
       <div id="indexnewsitemlist">
			<div class="wrap" name="wrap active">
<?php 
	include 'inc/prodlist.inc.php';
?>
			</div>
       </div>
    </div>
    <!--/最新上架宝贝-->
    <div id="clearboth"></div>
  </div>
  <!--/右侧框架-->
<?php 
	include 'inc/rightNav.inc.php';
?>
  <div id="clearboth"></div>
</div>

<script src="/assets/javascripts/switch.js"></script>
