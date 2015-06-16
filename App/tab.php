<!DOCTYPE html>
<html lang="en">
<head>
	<title>Security</title>
		<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css" media="screen">
		<link rel="stylesheet" type="text/css" href="../jeasyui/themes/default/easyui.css">
	    <link rel="stylesheet" type="text/css" href="../jeasyui/themes/icon.css">
	    <link rel="stylesheet" type="text/css" href="=../jeasyui/demo/demo.css">
		<script type="text/javascript" src="../jquery-1.10.2.js"></script>
	    <script type="text/javascript" src="../jeasyui/jquery.easyui.min.js"></script>
		<script language="javascript" type="script/javascript" src="../bootstrap/js/bootstrap.min.js" ></script>
		<script>
    $('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
    })
		</script>

</head>
<body>
    <ul class="nav nav-tabs" id='myTab'>
    <li><a href="#home" data-toggle="tab">Home</a></li>
    <li><a href="#profile" data-toggle="tab">Profile</a></li>
    <li><a href="#messages" data-toggle="tab">Messages</a></li>
    <li><a href="#settings" data-toggle="tab">Settings</a></li>
    </ul>
	
	<div class="tab-content">
<div class="tab-pane active" id="home">halaman home</div>
<div class="tab-pane" id="profile">halaman profile</div>
<div class="tab-pane" id="messages">halaman message</div>
<div class="tab-pane" id="settings">halaman setting</div>
</div>
</body>
</html>

