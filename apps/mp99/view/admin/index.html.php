<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="css/style.default.css">

    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">你的浏览器已经老掉牙了，请你选择 <a href="http://browsehappy.com/">升级你的浏览器</a> 或者 <a href="http://www.google.com/chromeframe/?redirect=true">安装谷歌浏览器</a> 以获得更好的浏览效果.</p>
        <![endif]-->

        <div class="bodywrapper">

            <!--begin topheader-->
            <div class="topheader  clearfix">
                <div class="left">
                <h1 class="logo">
                  In<span>Brand</span>
                </h1>
                <span class="slogan">betal 1.0</span>
                </div>
                
                <div class="right">
                    <a class="userinfo">
                    <img alt="" src="images/thumbs/avatar.png">
                    <span>{$_SESSION['username']}</span>
                    <em></em>
                    </a>
                    <div class="userinfodrop">
                     <em></em>
                     <div class="avatar">
                     </div>
                     <div class="userdata">
                      <h4>{$_SESSION['username']}</h4>
                      <!-- <span class="email">youremail@yourdomain.com</span> -->
                      <dl>
                        <dd><a href="javascript:handler('profile');">帐号设置</a></dd>
                        <dd><a href="javascript:handler('modifypwd');">修改密码</a></dd>
                        <dd><a href="javascript:handler('help');">帮助</a></dd>
                        <dd><a href="javascript:handler('logout');">退出</a></dd>
                      </dl>
                     </div>
                    </div>
                </div>
            </div>
            <!--end topheader-->

            <!--begin header-->
            <div class="header clearfix">
                <ul id="headermenu" class="headermenu">
                    <li class="current">
                      <a href="javascript:;" indexId="main">
                      <span class="icon icon-flatscreen"></span>
                      <span>首页</span>
                      </a>
                    </li>
                    <li>
                      <a href="javascript:;" indexId="user">
                      <span class="icon icon-chart"></span>
                      <span>用户</span>
                      </a>
                    </li>
                    <li>
                      <a href="javascript:;" indexId="message">
                      <span class="icon icon-message"></span>
                      <span>消息</span>
                      </a>
                    </li>
              </ul>
              <div class="headerwidget">
                <div class="costs">

                    <div class="todaycost">
                    <h4>当日流量</h4>
                    <h2>2343</h2>
                    </div>

                    <div class="remainsum">
                    <h4>剩余金额</h4>
                    <h2>￥0.00</h2>
                    </div>

                </div>
             </div>
            </div>
           <!--end header-->

           <div class="mainwrapper">
               <!--begin vertical navigate-->
               <div id="headersubmenu_main" class="vernav iconmenu">
                  <dl>
                    <dd class="current">
                        <a class="menu overview" href="#">概况</a>

                    </dd>
                    <dd>
                        <a class="menu" href="#ordersub">订单</a>
                        <span class="arrow-down"></span>
                        <dl id="ordersub">
                            <dd>
                            <a href="#">新建订单</a>
                            </dd>
                            <dd>
                            <a href="#">管理订单</a>
                            </dd>
                        </dl>

                    </dd>
                    <dd>
                        <a class="menu" href="#">投放</a>

                    </dd>
                    <dd>
                        <a class="menu" href="#">广告创意</a>
 
                    </dd>

                  </dl>
                  <!-- <a class="togglemenu"></a> -->
               </div>
               <!--end vertical navigate-->

               <!--begin vertical navigate-->
               <div id="headersubmenu_user" class="vernav iconmenu hide">
                  <dl>
                    <dd class="current">
                        <a class="menu overview" href="#">用户列表</a>

                    </dd>
                    <dd>
                        <a class="menu" href="#ordersub">订单</a>
                        <span class="arrow-down"></span>
                        <dl id="ordersub">
                            <dd>
                            <a href="#">新建订单</a>
                            </dd>
                            <dd>
                            <a href="#">管理订单</a>
                            </dd>
                        </dl>

                    </dd>
                    <dd>
                        <a class="menu" href="#">投放</a>

                    </dd>
                    <dd>
                        <a class="menu" href="#">广告创意</a>
 
                    </dd>

                  </dl>
                  <!-- <a class="togglemenu"></a> -->
               </div>
               <!--end vertical navigate-->

               <!--begin vertical navigate-->
               <div id="headersubmenu_message" class="vernav iconmenu hide">
                  <dl>
                    <dd class="current">
                        <a class="menu overview" href="#">消息列表</a>

                    </dd>
                    <dd>
                        <a class="menu" href="#ordersub">订单</a>
                        <span class="arrow-down"></span>
                        <dl id="ordersub">
                            <dd>
                            <a href="#">新建订单</a>
                            </dd>
                            <dd>
                            <a href="#">管理订单</a>
                            </dd>
                        </dl>

                    </dd>
                    <dd>
                        <a class="menu" href="#">投放</a>

                    </dd>
                    <dd>
                        <a class="menu" href="#">广告创意</a>
 
                    </dd>

                  </dl>
                  <!-- <a class="togglemenu"></a> -->
               </div>
               <!--end vertical navigate-->

               <!--begin centercontent-->
               <div class="centercontent">

                  <div class="contentwrapper  clearfix">
                     <!-- notification announcement -->
                      <div class="noticebar announcement">
                        <a class="close"></a>
                        <h3>Hello World</h3>
                        <p>欢迎使用后台管理系统.</p>
                      </div>

                      <!--cols two left content-->
                      <div class="two-third">
                         <div class="contenttitle2 no-mgt">
                         <h4>交易概览</h4>
                         </div>
                         <div class="mgb20">
                            <table class="stdtable overviewtable" cellspacing="0" cellpadding="0" border="0">
                            <colgroup>
                             <col class="even" width="25%"></col>
                             <col class="odd" width="25%"></col>
                             <col class="even" width="25%"></col>
                             <col class="odd" width="25%"></col>
                           </colgroup>
                           
                            <thead>
                            <tr>
                              <th>今日交易(￥)</th>
                              <th>昨日交易(￥)</th>
                              <th>总交易额(￥)</th>
                              <th>账户余额(￥)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                              <td>1458.00</td>
                              <td>1245.00</td>
                              <td>256423.00</td>
                              <td>7562.00</td>
                            </tr>
                            </tbody>

                           </table>
                         </div>
                         <div id="chartplace" style="height:500px;">



                         </div>
                      </div>

                      <!--cols one right content-->
                      <div class="one-third no-mgr">
                        <!--widget box-->
                         <div class="widgetbox">
                              <div class="title no-mgt">
                                  <h4>网站概览</h4>
                              </div>
                              <dl>
                                  <dt class="clearfix">
         
                                      <div class="fr">
                                      <a href="javascript:;" class="button"><span>查看全部</span></a>
                                      </div>
                                      <div class="fl">
                                      <a href="javascript:;" class="button"><span>添加网站</span></a>
                                      </div>
                                      <span class="number">20</span>
                                  </dt>
                                  <dd class="clearfix">
                                     <div class="three-fourth">
                                       <div class="left">
                                        <a class="avatar" href="#"><img src="images/default/1.jpg"/></a>
                                        <p class="name"><a href="#">yylady.cn</a></p>
                                        <p class="describe">女性网站</p>
                                        </div>
                                     </div>

                                     <div class="one-fourth no-mgr">
                                        <div class="right">
                                          <p class="num"><span>5</span></p>
                                          <p>频道</p>
                                        </div>
                                     </div>
                                     
                                  </dd>
                                  <dd class="clearfix last">
                                     <div class="three-fourth">
                                       <div class="left">
                                        <a class="avatar" href="#"><img src="images/default/1.jpg"/></a>
                                        <p class="name"><a href="#">gmw.cn</a></p>
                                        <p class="describe">门户网站</p>
                                        </div>
                                     </div>

                                     <div class="one-fourth no-mgr">
                                        <div class="right">
                                          <p class="num"><span>12</span></p>
                                          <p>频道</p>
                                        </div>
                                     </div>
                                     
                                  </dd>
                              </dl>
                         </div>
                         <!--widget box-->
                         <div class="widgetbox">
                           <div class="title"><h4>创意概览</h4></div>
                           <dl>
                                  <dt class="clearfix">
         
                                      <div class="fr">
                                      <a href="javascript:;" class="button"><span>查看全部</span></a>
                                      </div>
                                      <div class="fl">
                                      <a href="javascript:;" class="button"><span>添加创意</span></a>
                                      </div>
                                      <span class="number">11</span>
                                  </dt>
                                  <dd class="clearfix">
                                     <div class="three-fourth">
                                       <div class="left">
                                        <p class="name"><a href="#">my add1</a></p>
                                        <p class="describe">swf文件</p>
                                        </div>
                                     </div>

                                     <div class="one-fourth no-mgr">
                                        <div class="right">
                                          <p class="text"><span>通过</span></p>
                                          <p>2012-09-16 </p>
                                        </div>
                                     </div>
                                     
                                  </dd>
                                  <dd class="clearfix">
                                     <div class="three-fourth">
                                       <div class="left">
                                        <p class="name"><a href="#">my add2</a></p>
                                        <p class="describe">文字链</p>
                                        </div>
                                     </div>

                                     <div class="one-fourth no-mgr">
                                        <div class="right">
                                          <p class="text"><span>审核中</span></p>
                                          <p>2012-09-18 </p>
                                        </div>
                                     </div>
                                     
                                  </dd>
                                  <dd class="clearfix last">
                                     <div class="three-fourth">
                                       <div class="left">
                                        <p class="name"><a href="#">my add3</a></p>
                                        <p class="describe">gif图片</p>
                                        </div>
                                     </div>

                                     <div class="one-fourth no-mgr">
                                        <div class="right">
                                          <p class="text"><span>待审核</span></p>
                                          <p>2012-09-12</p>
                                        </div>
                                     </div>
                                     
                                  </dd>
                              </dl>
                           
                         </div>



                      </div>

                      


                  </div>

               </div>
               <!--end centercontent-->
            </div>

        </div>

<!--
<div id="dialog-profile">
  <form action="">
    昵称：<input id="nickname" value="{$nickname}" />
  </form>
</div>
-->

        <!--[if lt IE 9]>
            <script src="http://lib.sinaapp.com/js/jquery/1.9.0/jquery.min.js"></script>
            <script type="text/javascript">window.jQuery || document.write(unescape('%3Cscript src="{$_www_url}js/jquery-1.4.4.min.js"%3E%3C/script%3E'))</script>
        <![endif]-->
        <!--[if gte IE 9]>
            <script src="http://lib.sinaapp.com/js/jquery/1.9.0/jquery.min.js"></script>
            <script type="text/javascript">window.jQuery || document.write(unescape('%3Cscript src="{$_www_url}js/jquery-2.0.0b1.min.js"%3E%3C/script%3E'))</script>
        <![endif]-->
        <!--[if !IE]><!-->
            <script src="http://lib.sinaapp.com/js/jquery/1.9.0/jquery.min.js"></script>
            <script src="http://code.jquery.com/jquery-migrate-1.1.1.js"></script>
            <script type="text/javascript">window.jQuery || document.write(unescape('%3Cscript src="{$_www_url}js/jquery-2.0.0b1.min.js"%3E%3C/script%3E'))</script>
        <![endif]-->
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
        <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
        <script src="js/custom/general.js" type="text/javascript"></script>
        <script src="{$_www_url}js/jquery.uniform.min.js" type="text/javascript"></script>
        <link rel="stylesheet" href="{$_www_url}css/uniform/css/uniform.default.min.css" />
    </body>
</html>
