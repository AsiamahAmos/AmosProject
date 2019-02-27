<?php
session_start();
include_once('connect_db.php');
include_once('alrt.php');
include_once ('ev.php');
include_once('functions.php');
if(isset($_SESSION['username'])){
$id=$_SESSION['pharmacist_id'];
$fname=$_SESSION['first_name'];
$lname=$_SESSION['last_name'];
$sid=$_SESSION['staff_id'];
$user=$_SESSION['username'];
}else{
header("location:http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php");
exit();
}
?>
<?php
include('connect_db.php');

$result = mysql_query("SELECT * FROM stock WHERE quantity <=50  OR  stock.expiry_date >= DATE(now())
AND
    stock.expiry_date <= DATE_ADD(DATE(now()), INTERVAL 1 YEAR)") 
                or die(mysql_error());

                
$num_rows = mysql_num_rows($result);

 ?>

<!DOCTYPE html>
<html>
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $user;?> - Pharmado Ent</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
<link rel="stylesheet" type="text/css" href="css/fullcalendar.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="js/jquery.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="jquery-ui.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="moment.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
 <script src="fullcalendar.min.js"></script>
  
<link rel="stylesheet" type="text/css" href="style/ystyle.css">
<link rel="stylesheet" href="style/tyle.css" type="text/css" media="screen" /> 
<link rel="stylesheet" type="text/css" href="style/ashboard_styles.css"  media="screen" />
<link rel='stylesheet prefetch' href='http://fonts.googleapis.com/css?family=Cherry+Swash'>
 <link type="text/css" rel="stylesheet" href="tye.css"/>
</head>
<body>
<div id="content" style="background-image: url('h.jpg')!important;background-repeat: no-repeat;background-size: cover;"> 

<div id="header">

<h1><a href="#"><img src="images/hd_logo.jpg"></a> Pharmado Ent.</h1>
</div>

<div id="button" >
 
<nav>

			<a href="pharmacist.php">Dashboard</a>
			
			<a href="stock_pharmacist.php">Stock</a>
                        <a href="prescription.php">Prescription</a>
		        <a href="payment.php"target="_top">Reports</a>
                         <a href="notify.php" class="id1">Notification <sup style="font-color :white;background: red; border-radius:30%;font-weight:bold; width:20px;height:20px;border:2px solid white;padding: 8px;"><?php
echo "$num_rows";?> </sup></a>
                        <a href="logout.php">Logout</a>
	                 <div class="animation start-home"></div>
</nav>	
</div>


 <script>
   
  $(document).ready(function() {
   var calendar = $('#calendar').fullCalendar({
    editable:true,
    header:{
     left:'prev,next today',
     center:'title',
     right:'month,agendaWeek,agendaDay'
    },
    events: 'load.php',
    selectable:true,
    selectHelper:true,
    select: function(start, end, allDay)
    {
     var title = prompt("Enter Event Title");
     if(title)
     {
      var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
      var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
      $.ajax({
       url:"insert.php",
       type:"POST",
       data:{title:title, start:start, end:end},
       success:function()
       {
        calendar.fullCalendar('refetchEvents');
        alert("Added Successfully");
       }
      })
     }
    },
    editable:true,
    eventResize:function(event)
    {
     var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
     var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
     var title = event.title;
     var id = event.id;
     $.ajax({
      url:"update.php",
      type:"POST",
      data:{title:title, start:start, end:end, id:id},
      success:function(){
       calendar.fullCalendar('refetchEvents');
       alert('Event Update');
      }
     })
    },

    eventDrop:function(event)
    {
     var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
     var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
     var title = event.title;
     var id = event.id;
     $.ajax({
      url:"update.php",
      type:"POST",
      data:{title:title, start:start, end:end, id:id},
      success:function()
      {
       calendar.fullCalendar('refetchEvents');
       alert("Event Updated");
      }
     });
    },

    eventClick:function(event)
    {
     if(confirm("Are you sure you want to remove it?"))
     {
      var id = event.id;
      $.ajax({
       url:"delete.php",
       type:"POST",
       data:{id:id},
       success:function()
       {
        calendar.fullCalendar('refetchEvents');
        alert("Event Removed");
       }
      })
     }
    },

   });
  });
   
  </script>
  <br/>
<div style="width:1250px">
  <h2 align="center"><a href="#">EVENT CALENDAR</a></h2>
  <br />
  <div style="width:900px; margin-left:0px;display:inline-block;" class="container"><br><br>
   <div id="calendar"></div>
</div>
  <div  style="margin-left;width:340px; background:dimgray;margin-top:10px;display:inline-block;vertical-align:top;border:5px solid hotpink;color:white">  
<input style="float:right" value="Delete" type="submit" form="form1"/>

  <h3><center>Upcomming Events</center></h3>

<form id="form1" method="post" action="pharmacist.php">

<?php 
$sql=mysql_query("SELECT * FROM events ORDER BY id DESC LIMIT 10");
          WHILE($row=mysql_fetch_array($sql)){
$date = $row['start_event'];
$dat = date('(h:i:s A)    d, F, Y', strtotime($date));

?>
   <input name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $row['id']; ?>">&nbsp <?php echo '<b style="font-size:16px;">'.$row['title'].'&nbsp&nbsp'.'     TIME... '.$dat.'</b>'.'<br>'.'<hr>';
}
?>
    </div>
</div>
<?php
$del_id = $_POST['checkbox']; 
$detectinglocations = 'events';

foreach($del_id as $value){
   $sql = "DELETE FROM ".$detectinglocations." WHERE id='".$value."'";
   $result = mysql_query($sql);
}
?>
</form>

<div id="footer" align= "center"> <b>Pharmado Ent &copy 2018. Copyright All Rights Reserved</b></div>
</div>
</body>

<style>


input:hover{
background-color:pale;
box-shadow: 0px 0px 11px 4px #3DE7ED;
-moz-box-shadow: 0px 0px 11px 4px #3DE7ED;
-webkit-box-shadow: 0px 0px 11px 4px #3DE7ED;
}

#cc{
text-align:center;
}
#rr{
text-align:right;
}
nav {
	margin-left:px;


        margin-top: px;
	position: relative;
	border-bottom:5px solid blue;
         width: auto;

	height: 70px;

	background-color: #34495e;

	border-radius: px;

	font-size: 0;
        list-style-type: none;      
}


       
        nav a {
	line-height: 65px;
	
         height: 100%;
	
         font-size: 17px;

	 display: inline-block;

	 position: relative;
	
         z-index: 1;

	 text-decoration: none;
	
         text-transform: uppercase;
	
         text-align: center;
	
        text-weight: bold;
         color: white;

	cursor: pointer;
}


nav .animation {
position: absolute;

	height: 100%;

	top: 0;
	z-index: 0;
	
transition: all .5s ease 0s;

	border-radius: 8px;
}

a:nth-child(1) {
width: 120px;
}

a:nth-child(2) {
width: 100px;
}

a:nth-child(3) {
width: 150px;
}

a:nth-child(4) {
width: 110px;
}

a:nth-child(5) {
width: 150px;
}
a:nth-child(6) {
width: 110px;
}


nav .start-home, a:nth-child(1):hover~.animation {
width: 120px;
	
left: 0;
	
background-color: teal;
}


nav .start-about, a:nth-child(2):hover~.animation {

        width: 100px;

	left: 120px;

	background-color: springgreen;
}


nav .start-blog, a:nth-child(3):hover~.animation {
	width: 150px;
	
left: 220px;
	
background-color: #3498db;
}


nav .start-portefolio, a:nth-child(4):hover~.animation {
	width: 110px;
	
left: 370px;
	
background-color:hotpink;
}

nav .start-contact, a:nth-child(5):hover~.animation {
	width: 150px;

	left: 480px;
	
background-color: blue;
}
nav .start-contact, a:nth-child(6):hover~.animation {
	width: 110px;

	left: 630px;
	
background-color: orange;
}



body
 {
	font-size: 14px;
	
font-family: times;
	
background: #aaa;
}


h1 {
text-align: center;
	
margin: 40px 0 40px;
	
text-align: center;
	
font-size: 50px;
	
color: #ecf0f1;
	
text-shadow: 2px 2px 4px #000000;
	
font-family: 'Cherry Swash', cursive;
}



p {
    position: absolute;
   
 bottom: 20px;
  
  width: 100%;
  
  text-align: center;
  
  color: #ecf0f1;
  
  font-family: 'Cherry Swash',cursive;
   
 font-size: 16px;
}

span {
    color: white;
font-weight:bold;
}

body{
margin-top: 0px;
padding-top: 0px;}
#content{
margin-left: auto;
margin-right: auto;
background: #E8E8E8;
width: 1250px;
height:auto;

}
#header {
width: 1250px;
height: 96px;
background: green;
border-bottom: solid 5px blue;
}
#header h1{
margin: 0px;
padding-top: 5px;
margin-left: 50px;
color: #fff;
}
#header  img {float:left; height: 90px; width: 150px;}
#left_colu {
clear: all;
float: left;
width: 200px;
height: 501px;
background:#E8E8E8;
border-right:solid 2px #E8E8E8;
}

#{
clear: all;
width: 780px;
height: 470px;
background: black;
}
#footer{
width: 1250px;
height: 60px;
background:green;
clear: all;
border-top: solid 5px blue;
align: centered;
margin-left: auto;
margin-right: auto;
margin-top: auto;
text-weight: bold;
position:relative;

}
.container {
Background:dimgray;
Color:white;
font-size:20px;
border:5px solid hotpink;
}
label{
Color: black;
}
div.fc-row.fc-week.fc-widget-content{
color:black;
}
.fc-event{
background:red;
}
.fc th, .fc td{
border-width:1px;
border-color:hotpink;
}
element.style{
height:620px;
}
.fc-unthemed td.fc-today{
background:darkslateblue;
}

</style>


</html>
