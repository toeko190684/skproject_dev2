<?php
/*
 created by toeko triyanto
 this file is find menu for all user acces
*/
function menu(){
		if($_SESSION[grade_id]=="*"){
		    $app = mysql_query("select * from sec_app where pro_id=$_SESSION[pro_id] order by urut");
		}else{
		    $app = mysql_query("SELECT distinct c.app_id,c.app_name FROM sec_user_rules a
								left join sec_app_module b on a.module_id=b.module_id
								left join sec_app c on c.app_id=b.app_id
								WHERE user_id='$_SESSION[user_id]' and 
								c.pro_id='$_SESSION[pro_id]' and a.r=1 order by c.app_name");			
							   
							   
							   
		}
		
		echo "<div class='navbar navbar-inverse' id='menu' style='top:0px ; position:fixed; left:0px; right:0px;width:100%;height:50px;z-index:100' >
			      <div class='navbar-inner'>
			        <div class='container'>
			          <button type='button' class='btn btn-navbar' data-toggle='collapse' data-target='.nav-collapse'>
			            <span class='icon-bar'></span>
			            <span class='icon-bar'></span>
			            <span class='icon-bar'></span>
			          </button>
			          <a class='brand' href='#'><img src='../images/logo_company.jpg' width=150></i></a>
			          <div class='nav-collapse collapse'>					
							    <ul class=\"nav pull-right\">
								  <li class=\"dropdown\">
									<a class=\"dropdown-toggle\"  data-toggle=\"dropdown\" href=\"#\">
										   <i class=\"icon-user icon-white\"></i> $_SESSION[user_id]
										<b class=\"caret\"></b>
									</a>
									<ul class=\"dropdown-menu\">
										<li><a href=\"?r=home\">Profil</a></li>
										<li><a href=\"?r=home\">Change Password</a></li>
										<li><a href=\"logout.php\">Logout</a></li>
									</ul>
								  </li>
								</ul>	
			            <ul class='nav'>
						  <li class='active'><a href='?r=home'><i class=\"icon-home\"></i></a></li>";
		                  while($rapp = mysql_fetch_array($app)){ 
			              echo   "<li class='dropdown'>
					                <a href='#' class='dropdown-toggle' data-toggle='dropdown'>$rapp[app_name] <b class='caret'></b></a>
					                <ul class='dropdown-menu'>";
									   if($_SESSION[grade_id]=="*"){
									         $module = mysql_query("select * from sec_app_module where app_id='$rapp[app_id]'");
										}else{
											$module = mysql_query("SELECT DISTINCT a.* FROM sec_app_module a 
																   left join sec_user_rules b on a.module_id=b.module_id 
																   WHERE b.user_id='$_SESSION[user_id]' and a.app_id='$rapp[app_id]'
																   and b.r=1");																	
										}
									   while($rmodule = mysql_fetch_array($module)){
											echo "<li><a href='index.php$rmodule[link]&mod=$rmodule[module_id]'>$rmodule[module_name]</a></li>";
									  }
					      echo "    </ul>
					              </li>";
						  }
		echo "	            </ul>
			          </div><!--/.nav-collapse -->
			        </div>
			      </div>
			    </div>";
}
?>

