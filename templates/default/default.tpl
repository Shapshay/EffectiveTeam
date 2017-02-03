<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <base href="http://{BASE_URL}">
	<!--                       CSS                       -->
	<link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
	<!-- Main Stylesheet -->
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
	<!-- Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid -->
	<link rel="stylesheet" href="css/invalid.css" type="text/css" media="screen" />
	<!-- Internet Explorer Fixes Stylesheet -->
	<!--[if lte IE 7]>
	<link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
	<![endif]-->
	<link rel="stylesheet" href="styles2.css" type="text/css" media="screen" />
	<!--                       Javascripts                       -->
	


	<!-- jQuery -->


<!-- 	<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
jQuery Configuration
<script type="text/javascript" src="js/simpla.jquery.configuration.js"></script> -->



<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="../js/sidebar.js"></script>
<script src="../js/bootstrap/bootstrap.min.js"></script>


	<script type="text/javascript" src="inc/will_pickdate/jquery.mousewheel.js"></script>
	<script type="text/javascript" src="inc/will_pickdate/will_pickdate.js"></script>
	
<script type="text/javascript">
 
        $(function(){
            var timerId2 = setInterval(function() {
                RefreshMsgs();
            }, 2000);
        });
        function RefreshMsgs() {
            var u_id = {ROOT_ID};
            $.post("inc/refreshMsgs.php", {u_id:u_id},
                    function(data){
                        console.log(data);
                        var obj = jQuery.parseJSON(data);
                        if(obj.result=='OK'){
                            $('#my_msg_all').text(obj.html);
                            $('#my_msg_all').attr("title", obj.html);
                            if(obj.num>0){
                                blinkTitle(obj.html,"{META_TITLE_JS}",500);
                            }
                            else{
                                blinkTitleStop();
                                document.title = "{META_TITLE_JS}";
                            }
                        }
                    });
        } 
</script>

	<!-- ALERT -->
	<link rel="stylesheet" href="inc/swetalert/sweetalert.css" />
	<script src="inc/swetalert/sweetalert.min.js"></script>
	<!-- /ALERT -->

	<!-- Data Table -->
	<link rel="stylesheet" href="adm/inc/data_table/jquery.dataTables.min.css" />
	<script src="adm/inc/data_table/jquery.dataTables.min.js"></script>

	<!-- Calendar -->
	<link href="inc/will_pickdate/style.css" media="screen" rel="stylesheet" type="text/css"/>

    <!-- File Upload -->
    <script language="javascript" type="text/javascript" src="js/ajaxfileupload.js"></script>

    <!-- Galery -->
    <script src="js/jsibox/jsibox_basic.js"></script>

	<!-- Words Cloud -->
    <link rel="stylesheet" type="text/css" href="js/jqcloud/jqcloud.css" />
	<script src="js/jqcloud/jqcloud-1.0.0.js"></script>

    <!-- Blink Title -->
    <script src="js/blinkTitle.js"></script>

    <!-- My scripts -->
    <script src="inc/func.js"></script>

<!-- NEW STYLE REDESIGN -->
<link href="../css/bootstrap/bootstrap.css" media="screen" rel="stylesheet" type="text/css"/>
<link href="../css/default.css" media="screen" rel="stylesheet" type="text/css"/>
<!-- NEW STYLE REDESIGN -->



    {AUTH}
	{META}
	{META_HTML}
	 
 

</head>
<body>

<div id="wrapper">

	<div id="sidebar-wrapper">
		<div class="block">
			<div class="buttom_menu">
				<img id="menu" src="../images/cancel.png" alt="" class="menu_buttom active">
			</div>
			<div class="logo">
				<a href="/"><img src="images/logo.png" alt="Perch 1.0" width="100"></a>
				<h1 id="sidebar-title" align="center"><a href="#!">TEAM 1.0</a></h1>
			</div> 

			<div id="profile-links">
				<p>Здравствуйте, <a title="{ROOT_NAME}">{ROOT_NAME}</a> ,<br>
					у Вас <a id="my_msg_all" title="{MSG_NUM}">{MSG_NUM}</a></p> 
				<a href="/index.php?exit" title="Выход">Выход</a>
			</div>

			<ul id="main-nav">  <!-- Accordion Menu -->
				{MENU_PAGES}
			</ul> 

		</div> <!-- block -->
	</div> <!-- sidebar-wrapper -->

 
	<div id="page-content-wrapper">
		<h2>{PAGE_TITLE}</h2>
		<div class="main-cintent">  

			{CONTENT}

			<div id="footer">
				<small> <!-- Remove this notice or replace it with whatever you want -->
					© Copyright 2017 Авто Клуб Казахстана | <a href="#">Top</a>
				</small>
			</div>
		</div> 
	</div>



</div> <!-- wrapper -->




</body>
</html>