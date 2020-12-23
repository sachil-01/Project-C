<?php

    include('header.php');


	require 'includes/dbh.inc.php';
	$with_any_one_of = "";
	$with_the_exact_of = "";
	$without = "";
	$starts_with = "";
	$search_in = "";
	$advance_search_submit = "";
	
	$queryCondition = "";
	if(!empty($_POST["search"])) {
		$advance_search_submit = $_POST["advance_search_submit"];
		foreach($_POST["search"] as $k=>$v){
			if(!empty($v)) {

				$queryCases = array("with_any_one_of","with_the_exact_of","without","starts_with");
				if(in_array($k,$queryCases)) {
					if(!empty($queryCondition)) {
						$queryCondition .= " AND ";
					} else {
						$queryCondition .= " WHERE ";
					}
				}
				switch($k) {
					case "with_any_one_of":
						$with_any_one_of = $v;
						$wordsAry = explode(" ", $v);
						$wordsCount = count($wordsAry);
						for($i=0;$i<$wordsCount;$i++) {
							if(!empty($_POST["search"]["search_in"])) {
								$queryCondition .= $_POST["search"]["search_in"] . " LIKE '%" . $wordsAry[$i] . "%'";
							} else {
								$queryCondition .= "plantName LIKE '" . $wordsAry[$i] . "%' OR plantDesc LIKE '" . $wordsAry[$i] . "%'";
							}
							if($i!=$wordsCount-1) {
								$queryCondition .= " OR ";
							}
						}
						break;
					case "with_the_exact_of":
						$with_the_exact_of = $v;
						if(!empty($_POST["search"]["search_in"])) {
							$queryCondition .= $_POST["search"]["search_in"] . " LIKE '%" . $v . "%'";
						} else {
							$queryCondition .= "plantName LIKE '%" . $v . "%' OR plantDesc LIKE '%" . $v . "%'";
						}
						break;
					case "without":
						$without = $v;
						if(!empty($_POST["search"]["search_in"])) {
							$queryCondition .= $_POST["search"]["search_in"] . " NOT LIKE '%" . $v . "%'";
						} else {
							$queryCondition .= "plantName NOT LIKE '%" . $v . "%' AND plantDesc NOT LIKE '%" . $v . "%'";
						}
						break;
					case "starts_with":
						$starts_with = $v;
						if(!empty($_POST["search"]["search_in"])) {
							$queryCondition .= $_POST["search"]["search_in"] . " LIKE '" . $v . "%'";
						} else {
							$queryCondition .= "plantName LIKE '" . $v . "%' OR plantDesc LIKE '" . $v . "%'";
						}
						break;
					case "search_in":
						$search_in = $_POST["search"]["search_in"];
						break;
				}
			}
		}
	}
	$orderby = " ORDER BY idAd desc"; 
    $sql = "SELECT * FROM Advertisement a JOIN User u ON a.userId = u.idUser JOIN AdImage ai ON a.idAd = ai.idAdvert" . $queryCondition;
	$result = mysqli_query($conn,$sql);
	
?>
<html>
	<head>
	<title>Advanced Search using PHP</title>
	</head>
	<body>
		<h2>Advanced Search using PHP</h2>
    <div>      
			<form name="frmSearch" method="post" action="filters.php">
			<input type="hidden" id="advance_search_submit" name="advance_search_submit" value="<?php echo $advance_search_submit; ?>">
			<div class="search-box">
				<label class="search-label">With Any One of the Words:</label>
				<div>
					<input type="text" name="search[with_any_one_of]" class="demoInputBox" value="<?php echo $with_any_one_of; ?>"	/>
					<span id="advance_search_link" onClick="showHideAdvanceSearch()">Advance Search</span>
				</div>				
				<div id="advanced-search-box" <?php if(empty($advance_search_submit)) { ?>style="display:none;"<?php } ?>>
					<label class="search-label">With the Exact String:</label>
					<div>
						<input type="text" name="search[with_the_exact_of]" id="with_the_exact_of" class="demoInputBox" value="<?php echo $with_the_exact_of; ?>"	/>
					</div>
					<label class="search-label">Without:</label>
					<div>
						<input type="text" name="search[without]" id="without" class="demoInputBox" value="<?php echo $without; ?>"	/>
					</div>
					<label class="search-label">Starts With:</label>
					<div>
						<input type="text" name="search[starts_with]" id="starts_with" class="demoInputBox" value="<?php echo $starts_with; ?>"	/>
					</div>
					<label class="search-label">Search Keywords in:</label>
					<div>
						<select name="search[search_in]" id="search_in" class="demoInputBox">
							<option value="">Select Column</option>
							<option value="plantName" <?php if($search_in=="plantName") { echo "selected"; } ?>>plantName</option>
							<option value="plantDesc" <?php if($search_in=="plantDesc") { echo "selected"; } ?>>plantDesc</option>
						</select>
					</div>
				</div>
				
				<div>
					<input type="submit" name="go" class="btnSearch" value="Search">
				</div>
			</div>
			</form>	
			<?php while($row = mysqli_fetch_assoc($result)) { ?>
			<div>
				<div><strong><?php echo $row["plantName"]; ?></strong></div>
				<div class="result-plantDesc"><?php echo $row["plantDesc"]; ?></div>
			</div>
			<?php } ?>
		</div>
	</body>
</html>

<?php 
    include('footer.php');
    include('feedback.php');
?>