<?php
# SETTINGS #############################################################################
$moduleName = "search_tasks";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "result_row" => $prefix . "result_row.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "result" => $prefix . "result.tpl",
));

# MAIN ##################################################################################
$search = false;
if(isset($_POST['word'])){
	$tpl->assign("SEARCH_WORD", $_POST['word']);
	$i = 0;

    $rows = $dbc->dbselect(array(
            "table"=>"tasks",
            "select"=>"tasks.*,
			        priors.title as prior,
			        departaments.title as dep,
			        users.name as user,
			        statuses.title as stat_text",
            "joins"=>"LEFT OUTER JOIN priors ON tasks.prior_id = priors.id
                    LEFT OUTER JOIN users ON tasks.u_id = users.id
                    LEFT OUTER JOIN departaments ON tasks.d_id = departaments.id
                    LEFT OUTER JOIN statuses ON tasks.status = statuses.id",
            "where"=>"tasks.view = 1 AND (tasks.title LIKE '%".$_POST['word']."%' OR tasks.description LIKE '%".$_POST['word']."%' 
                    OR tasks.res_text LIKE '%".$_POST['word']."%')",
            "order"=>"tasks.date_create DESC"
        )
    );
    //echo $dbc->outsql;
    $numRows = $dbc->count;
    if ($numRows > 0) {
        foreach ($rows as $row) {
            $tpl->assign("RESULT_NAME", $row['title']);
            $tpl->assign("RESULT_DESC", $row['description']);
            $tpl->assign("RESULT_DATE", $row['date_create']);
            $tpl->assign("RESULT_URL", "/" . getItemCHPU(2184, 'pages') . "/?item=" . $row['id']);

            $tpl->parse("SEARCH_RESULTS", "." . $moduleName . "result_row");
            $i++;

        }

    }
    else{
        $tpl->assign("SEARCH_RESULTS", '');
    }
    $tpl->assign("TOTAL_FOUND", $i);
    $tpl->parse("SEARCH_SHOW", ".".$moduleName."result");
}
else{
	$tpl->assign("SEARCH_WORD", '');
	$tpl->assign("SEARCH_SHOW", '');
}

$words_cloud = '';
$rows2 = $dbc->dbselect(array(
        "table"=>"word_cloud",
        "select"=>"*",
        "order" => "num DESC",
        "limit" => 50
    )
);
foreach ($rows2 as $row2) {
    $words_cloud.= '{text: "'.$row2['word'].'", weight: '.$row2['num'].', link:{href:"javascript:CloudSearch(\''.$row2['word'].'\');", target:"_self"} },';
}
$tpl->assign("WORDS_CLOUD", $words_cloud);

$tpl->parse("META_HTML", ".".$moduleName."html");
$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>