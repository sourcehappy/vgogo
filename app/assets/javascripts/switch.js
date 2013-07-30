/**************************************************
 * 用法：
 *count:图片数量，
 *wrapId:包裹图片的DIV的className
 *ulId:按钮DIV class,
 *infoId：信息栏 
 *stopTime：每张图片停留的时间
 *SWTICH.scroll(count,wrapId,ulId,infoId,stopTime);
 **************************************************/
var DOM = KISSY.DOM  ;
var Anim = KISSY.Anim ;

var SWTICH = function() {
	function id(name) {return DOM.query("."+name)[0];}
	//遍历函数

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
		 // 启动动画
		anim=Anim(elem,{filter:"alpha(opacity=1)",opacity:"1"});
		anim.run();

	}
	function fadeOut(elem) {
		var anim;
		if(anim){
			 anim.stop();
		   }
		 // 启动动画
		anim=Anim(elem,{filter:"alpha(opacity=0)",opacity:"0"});
		anim.run();
		DOM.addClass(elem,"in");	
	}
	
	return {
		//count:图片数量,wrapId:包裹图片的DIV,ulId:按钮DIV,infoId：信息栏，stopTime：每张图片停留的时间
		scroll : function(count,wrapId,ulId,infoId,stopTime) {
			var self=this;
			var targetIdx=0;      //目标图片序号
			var curIndex=0;       //现在图片序号
			//添加Li按钮
			var frag=document.createDocumentFragment();
//			console.log(frag);
			this.num=[];    //存储各个li的应用，为下面的添加事件做准备
			this.info=id(infoId);
			for(var i=0;i<count;i++){
				(this.num[i]=frag.appendChild(document.createElement("li"))).innerHTML=i+1;
			}
//			console.log(id(ulId));
			id(ulId).appendChild(frag);
			//初始化信息
			this.img = id(wrapId).getElementsByTagName("a");
			//this.info.innerHTML=self.img[0].firstChild.title;
			this.num[0].className="on";
			
			//设置banner_bg为透明
			var banAnim=Anim(id("banner_bg"),{filter:"alpha(opacity=0.3)",opacity:"0.3"},0.01);
			banAnim.run();	
			//将除了第一张外的所有图片设置为透明
			each(this.img,function(elem,idx,arr){
				if (idx!=0) {
					DOM.addClass(elem,"in");
					var anim;
					if(anim){
						 anim.stop();
					   }
					 // 启动动画
					anim=Anim(elem,{filter:"alpha(opacity=0)",opacity:"0"},0.01);
					anim.run();
					DOM.addClass(elem,"in");	
				}
			});
			
			//为所有的li添加点击事件
			each(this.num,function(elem,idx,arr){
				elem.onclick=function(){
					self.fade(idx,curIndex);
					curIndex=idx;
					targetIdx=idx;
				}
			});
			//自动轮播效果
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

//瀑布流
var DOM = KISSY.DOM  ;
var $id = function(o){ return DOM.query("."+o)[0];};
var warpWidth = 210; //格子宽度
var margin = 10; //格子间距
function sort(el,childTagName){
         var h = []; //记录每列的高度
         var box = el.getElementsByTagName(childTagName);
         var minH = box[0].offsetHeight;
         var boxW = box[0].offsetWidth+margin;
  
  
         var n = 680 / boxW | 0; //计算页面能排下多少Pin
//  console.log(boxW);
  
         el.style.width = n * boxW - margin + "px";
   DOM.addClass(el,"isVisble");
//         console.log(el.style.visibility);
         for(var i = 0; i < box.length; i++) {//排序算法，有待完善
                  var boxh = box[i].offsetHeight; //获取每个Pin的高度
                  if(i < n) { //第一行特殊处理
                                    h[i] = boxh;
                                    box[i].style.top = 0 + 'px';
                                    box[i].style.left = (i * boxW) + 'px';
                  }
                  else {
                                    minH = Array.min(h); //取得各列累计高度最低的一列
                                    var minKey = getarraykey(h, minH);
                                    h[minKey] += boxh+margin ; //加上新高度后更新高度值
                                    box[i].style.top = minH+margin + 'px';
                                    box[i].style.left = (minKey * boxW) + 'px';
                  }
                  var maxH = Array.max(h);
                  var maxKey = getarraykey(h, maxH);
                  el.style.height = h[maxKey] +"px";//定位结束后更新容器高度
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
/* 返回数组中某一值的对应项数 */
function getarraykey(s, v) {
  var k=null;
        for(k in s) {
                if(s[k] == v) {
                        return k;
                }
        }
}
sort($id("wrap"),"div");