<?php
/**
 * 左侧导航用
 * 
 * @author Balsampear
 * @since 2013-4-21
 */

//左侧的分类导航数据
$classList = ClassData::getList(0);
//左侧专题
$leftArtList = Article::getList(1, 10);//显示3个

foreach ($classList as $row) {
?>
    <div id="vgogolefttag"> ▌<?php echo $row['name'];?> </div>
<?php 
		$clist = ClassData::getList($row['class_id']);
		echo '<div id="vgogolefttag2">';
		foreach ($clist as $row) {
    		echo '<a href="/list.php?cid='.$row['class_id'].'" target="_blank">'.$row['name'].'</a>';
		}
		echo '</div>';
	}
	
?>
    <!--/左侧标签-->
    <!--左侧专题-->
<?php 
	foreach ($leftArtList as $art) {
?>
    <div style="height:auto; padding-left:13px; padding-top:13px; padding-bottom:13px;border-right-width: 1px;
	border-bottom-width: 1px;
	border-top-style: none;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: none;
	border-right-color: #eeeeee;
	border-bottom-color: #eeeeee;">
		<a href="<?=$art['jump_url'] ? $art['jump_url'] : '/art.php?id='.$art['art_id']?>" target="_blank">
		<img src="<?=$art['main_img']?>"   width="180" title="<?=$art['title']?>" />
		</a>
	</div>
    <!--/左侧专题-->
<?php 
	}
?>