<?php
/**
 * 全局包含文件
 *
 * @author Balsampear
 * 2013-03-22
 */

//全局变量和配置

//top and tae sdk
// include("../top/ItemGetRequest.php");
// include("../top/TaobaokeItemsCouponGetRequest.php");
// include("../top/TaobaokeItemsGetRequest.php");
// include("../top/topclient.php");


/* 自身的公共基础类  - begin */
require("include/database.class.php");
require("include/db.class.php");
//模板和代码相关
require("html/html.class.php");

/* 自身的公共基础类  - end */


/* 自身的公共业务类 - begin */
require("oper/user.class.php");
require("oper/config.class.php");
require("oper/tag.class.php");
require("oper/classData.class.php");
require("oper/product.class.php");
require("oper/article.class.php");
require("oper/visit.class.php");


/* 自身的公共业务类 - end */


