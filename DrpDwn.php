<?php
//print_r($_POST);
$Armys = array(''=>'no choice','VC' => 'vampire_covenant.xml','DE'=> 'dread_elves.xml');
$file_name =$Armys[$_POST['Army']];
//echo $_POST['Army'], ' and ', $Armys[$_POST['Army']];
$i = 1;
echo '<select class="myunit"> <option value="0"></option>';
if (file_exists($file_name)) {
    $xml = simplexml_load_file($file_name);
	foreach ($xml->unit as $unit) {
	   echo '<option value="'.$i.'">'.(string) $unit['name'].'</option>';
	   $i++;
	}
} else {
    echo '<option value="1">Failed to open file</option>';
}
echo '</select>';
?>
