<?php
/**
 * ����������
 * 
 * @author Balsampear
 * @since 2013-4-22
 */
$topTagList = Tag::getListByProperty(3);
?>

    <!--����-->
    <form action="/search.php" method="get">
      <div id="vgogoleftseach">
        <input name="key" type="text" style="width:120px; height:14px;" value="<?=$key?>" />
        <input name="" type="submit" value="����" />
        ����������<a href="#" target="_blank">����ȹ</a><a href="#" target="_blank">����ȹ</a><a href="#" target="_blank">�黨ȹ</a><a href="#" target="_blank">��ȹ</a><a href="#" target="_blank">ѩ��ȹ</a><a name="vgogotop" id="vgogotop"></a> </div>
    </form>
    <!--/����-->
    <div style="clear:both;"></div>
    <!--��������-->
	<div id="vgogonavigation">
      <ul>
        <li><a href="/">��ҳ</a></li>
        <li><a href="/topic.php?tpid=2">���⳱Ʒ</a></li>
        <li><a href="/topic.php?tpid=7">���ڳ�Ʒ</a></li>
        <li><a href="#">V���ֶһ�</a></li>
        <li><a href="#">V�Ƕ�ר��</a></li>
        <li><a href="#">�̻�����</a></li>
      </ul>
    </div> <div style="clear:both;"></div>
    <!--/��������-->
    <!--��񵼺�-->
    <div  style="margin-top:10px; width:680px;">
<?php 
	foreach ($topTagList as $tag) {
?><a href="/tag.php?tid=<?=$tag['tag_id']?>"><img border="0" src="<?=$tag['img']?>" title="<?=$tag['name']?>" /></a><?php 
	}
?>
    </div> 
    <div style="clear:both;"></div>
    <!--/��񵼺�-->