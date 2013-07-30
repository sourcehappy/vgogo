<?php
/**
 * 顶部导航栏
 * 
 * @author Balsampear
 * @since 2013-4-22
 */
$topTagList = Tag::getListByProperty(3);
?>

    <!--搜索-->
    <form action="/search.php" method="get">
      <div id="vgogoleftseach">
        <input name="key" type="text" style="width:120px; height:14px;" value="<?=$key?>" />
        <input name="" type="submit" value="搜索" />
        热门搜索：<a href="#" target="_blank">连衣裙</a><a href="#" target="_blank">半身裙</a><a href="#" target="_blank">碎花裙</a><a href="#" target="_blank">长裙</a><a href="#" target="_blank">雪纺裙</a><a name="vgogotop" id="vgogotop"></a> </div>
    </form>
    <!--/搜索-->
    <div style="clear:both;"></div>
    <!--顶部导航-->
	<div id="vgogonavigation">
      <ul>
        <li><a href="/">首页</a></li>
        <li><a href="/topic.php?tpid=2">国外潮品</a></li>
        <li><a href="/topic.php?tpid=7">国内潮品</a></li>
        <li><a href="#">V积分兑换</a></li>
        <li><a href="#">V角度专题</a></li>
        <li><a href="#">商户合作</a></li>
      </ul>
    </div> <div style="clear:both;"></div>
    <!--/顶部导航-->
    <!--风格导航-->
    <div  style="margin-top:10px; width:680px;">
<?php 
	foreach ($topTagList as $tag) {
?><a href="/tag.php?tid=<?=$tag['tag_id']?>"><img border="0" src="<?=$tag['img']?>" title="<?=$tag['name']?>" /></a><?php 
	}
?>
    </div> 
    <div style="clear:both;"></div>
    <!--/风格导航-->