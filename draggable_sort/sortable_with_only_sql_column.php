<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/1.2.1/jquery-migrate.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<style>
	.column {
		width: 170px;
		float: left;
		padding-bottom: 100px;
	}
	.portlet {
		margin: 0 1em 1em 0;
		padding: 0.3em;
	}
	.portlet-header {
		padding: 0.2em 0.3em;
		margin-bottom: 0.5em;
		position: relative;
	}
	.portlet-toggle {
		position: absolute;
		top: 50%;
		right: 0;
		margin-top: -8px;
	}
	.portlet-content {
		padding: 0.4em;
	}
	.portlet-placeholder {
		border: 1px dotted black;
		margin: 0 1em 1em 0;
		height: 50px;
	}
</style>
<?php
	$action = $_GET["action"];
if($action == null || isset($action) && ($action == ""))
	$action = "index";
if($action == "index"){
?>
<ul id="sortable">
<?php
	$db = "draggable_sort";
	$db_connect=mysql_connect("localhost", "draggable_sort", "sorting");
	if (!mysql_select_db($db)) {
		mysql_query("CREATE DATABASE $db");
		mysql_select_db($db);
		die();
	}
	$result=mysql_query("select id,pic,sort from sortable_data order by sort asc", $db_connect);
	while($row = mysql_fetch_array($result)){
		$sort[] = $row;
	}
	foreach($sort as $key => $val){
?>
		<img src="<?php echo $val["pic"]?>" data-sort="<?php echo $val["id"]?>" class="sorting"></img>
<?php
	}
?>
<ul>
<?php
}
?>
<script>
/*start: function(event, ui) {
	var start_pos = ui.item.index();
	ui.item.data('start_pos', start_pos);
	get_start_index = ui.item.index();
	//console.log(ui.item.index());
},
change: function(event, ui) {
	//console.log("test");
},*/
$(function() {
	var get_start_index = null;
	var get_end_index= null;
	var new_sort_string = "";
	var key_string = "";
	$( "#sortable" ).sortable({
		//axis: "x",
		helper:function(e,ele){
			var _original = ele.children();
			var _helper =ele.clone();
			_helper.children().each(function(index){
				$(this).width(_original.eq(index).width());
			});
			return _helper;
		},
		update: function(event, ui) {
			$(".sorting").each(function(index){
				new_sort_string += (new_sort_string?',':'')+$(this).attr("data-sort");
				key_string += (key_string?',':'')+(index+1);
			});
			$.ajax({
				url:"function/sort.php?operation=sort_with_only",
				type:"POST",
				cache:false,
				data:{
					new_sort_string:new_sort_string,
					sort_key:key_string
				}
			}).done(function(data){
			});
			new_sort_string = "";
			key_string = "";
		}
	});
	$( "#sortable" ).disableSelection();
});
</script>