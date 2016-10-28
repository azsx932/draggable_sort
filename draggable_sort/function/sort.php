<?php
	if($_GET["operation"]){
		switch($_GET["operation"]){
			case "sort_with_only":
			if($_POST){
				$db = "draggable_sort";
				$db_connect=mysql_connect("localhost", "draggable_sort", "sorting");
				if (!mysql_select_db($db)) {
					mysql_query("CREATE DATABASE $db");
					mysql_select_db($db);
					die();
				}
				$new_sort_string = $_POST["new_sort_string"];
				$sort_key = $_POST["sort_key"];
				$new_col_sort = explode(",",$new_sort_string);
				$col_sort = explode(",",$sort_key);
				
				$result=mysql_query("select id,sort from sortable_data order by sort asc", $db_connect);
				$def_sort_data = array();
				while($row = mysql_fetch_array($result)){
					$def_sort_data[] = $row;
				}
				foreach($def_sort_data as $key => $val){
					if($val["id"] != $new_col_sort[$key]){
						mysql_query("update sortable_data set sort='".$col_sort[$key]."' where id='".$new_col_sort[$key]."'", $db_connect);
					}
				}
			}
			break;
			default:
			break;
		}
	}
?>