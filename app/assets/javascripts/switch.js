/**************************************************
 * �÷���
 *count:ͼƬ������
 *wrapId:����ͼƬ��DIV��className
 *ulId:��ťDIV class,
 *infoId����Ϣ�� 
 *stopTime��ÿ��ͼƬͣ����ʱ��
 *SWTICH.scroll(count,wrapId,ulId,infoId,stopTime);
 **************************************************/
var DOM = KISSY.DOM  ;
var Anim = KISSY.Anim ;

var SWTICH = function() {
	function id(name) {return DOM.query("."+name)[0];}
	//��������

	function each(arr, callback, thisp) {
		if (arr.forEach) {
			arr.forEach(callback, thisp);
		} else { 
			for (var i = 0, len = arr.length; i < len; i++) 
				callback.call(thisp, arr[i], i, arr);
		}
	}
	function fadeIn(elem) {
		DOM.removeClass(elem,"in");
		var anim;
		if(anim){
			 anim.stop();
		   }
		 // ��������
		anim=Anim(elem,{filter:"alpha(opacity=1)",opacity:"1"});
		anim.run();

	}
	function fadeOut(elem) {
		var anim;
		if(anim){
			 anim.stop();
		   }
		 // ��������
		anim=Anim(elem,{filter:"alpha(opacity=0)",opacity:"0"});
		anim.run();
		DOM.addClass(elem,"in");	
	}
	
	return {
		//count:ͼƬ����,wrapId:����ͼƬ��DIV,ulId:��ťDIV,infoId����Ϣ����stopTime��ÿ��ͼƬͣ����ʱ��
		scroll : function(count,wrapId,ulId,infoId,stopTime) {
			var self=this;
			var targetIdx=0;      //Ŀ��ͼƬ���
			var curIndex=0;       //����ͼƬ���
			//���Li��ť
			var frag=document.createDocumentFragment();
//			console.log(frag);
			this.num=[];    //�洢����li��Ӧ�ã�Ϊ���������¼���׼��
			this.info=id(infoId);
			for(var i=0;i<count;i++){
				(this.num[i]=frag.appendChild(document.createElement("li"))).innerHTML=i+1;
			}
//			console.log(id(ulId));
			id(ulId).appendChild(frag);
			//��ʼ����Ϣ
			this.img = id(wrapId).getElementsByTagName("a");
			//this.info.innerHTML=self.img[0].firstChild.title;
			this.num[0].className="on";
			
			//����banner_bgΪ͸��
			var banAnim=Anim(id("banner_bg"),{filter:"alpha(opacity=0.3)",opacity:"0.3"},0.01);
			banAnim.run();	
			//�����˵�һ���������ͼƬ����Ϊ͸��
			each(this.img,function(elem,idx,arr){
				if (idx!=0) {
					DOM.addClass(elem,"in");
					var anim;
					if(anim){
						 anim.stop();
					   }
					 // ��������
					anim=Anim(elem,{filter:"alpha(opacity=0)",opacity:"0"},0.01);
					anim.run();
					DOM.addClass(elem,"in");	
				}
			});
			
			//Ϊ���е�li��ӵ���¼�
			each(this.num,function(elem,idx,arr){
				elem.onclick=function(){
					self.fade(idx,curIndex);
					curIndex=idx;
					targetIdx=idx;
				}
			});
			//�Զ��ֲ�Ч��
			var itv=setInterval(function(){
				if(targetIdx<self.num.length-1){
					targetIdx++;
				}else{
					targetIdx=0;
					}
				self.fade(targetIdx,curIndex);
				curIndex=targetIdx;
				},stopTime);
			id(ulId).onmouseover=function(){ clearInterval(itv)};
			id(ulId).onmouseout=function(){
				itv=setInterval(function(){
					if(targetIdx<self.num.length-1){
						targetIdx++;
					}else{
						targetIdx=0;
						}
					self.fade(targetIdx,curIndex);
					curIndex=targetIdx;
				},stopTime);
			}
		},
		fade:function(idx,lastIdx){
			if(idx==lastIdx) return;
			var self=this;
			fadeOut(self.img[lastIdx]);
			fadeIn(self.img[idx]);
			each(self.num,function(elem,elemidx,arr){
				if (elemidx!=idx) {
					self.num[elemidx].className='';
				}else{
					id("list").style.background="";
					self.num[elemidx].className='on';
					}
			});
			//this.info.innerHTML=self.img[idx].firstChild.title;
		}
	}
}();
if (DOM.query(".banner_list").length > 0 ) {
	SWTICH.scroll(3,"banner_list","list","banner_info",4000);
}

//�ٲ���
var DOM = KISSY.DOM  ;
var $id = function(o){ return DOM.query("."+o)[0];};
var warpWidth = 210; //���ӿ��
var margin = 10; //���Ӽ��
function sort(el,childTagName){
         var h = []; //��¼ÿ�еĸ߶�
         var box = el.getElementsByTagName(childTagName);
         var minH = box[0].offsetHeight;
         var boxW = box[0].offsetWidth+margin;
  
  
         var n = 680 / boxW | 0; //����ҳ�������¶���Pin
//  console.log(boxW);
  
         el.style.width = n * boxW - margin + "px";
   DOM.addClass(el,"isVisble");
//         console.log(el.style.visibility);
         for(var i = 0; i < box.length; i++) {//�����㷨���д�����
                  var boxh = box[i].offsetHeight; //��ȡÿ��Pin�ĸ߶�
                  if(i < n) { //��һ�����⴦��
                                    h[i] = boxh;
                                    box[i].style.top = 0 + 'px';
                                    box[i].style.left = (i * boxW) + 'px';
                  }
                  else {
                                    minH = Array.min(h); //ȡ�ø����ۼƸ߶���͵�һ��
                                    var minKey = getarraykey(h, minH);
                                    h[minKey] += boxh+margin ; //�����¸߶Ⱥ���¸߶�ֵ
                                    box[i].style.top = minH+margin + 'px';
                                    box[i].style.left = (minKey * boxW) + 'px';
                  }
                  var maxH = Array.max(h);
                  var maxKey = getarraykey(h, maxH);
                  el.style.height = h[maxKey] +"px";//��λ��������������߶�
         }
         for(var i = 0; i < box.length; i++) {
      DOM.addClass(box[i],"isVisble");
         }
}
Array.min=function(array)
{
    return Math.min.apply(Math,array);
}
Array.max=function(array)
{
    return Math.max.apply(Math,array);
}
/* ����������ĳһֵ�Ķ�Ӧ���� */
function getarraykey(s, v) {
  var k=null;
        for(k in s) {
                if(s[k] == v) {
                        return k;
                }
        }
}
sort($id("wrap"),"div");