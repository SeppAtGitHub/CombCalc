<?php
if (count($_POST['A']) > 0)
{
    $i = 0;
    foreach ($_POST['A'] as $row)
    {
		echo "Index $i of A<br />\n";
        echo "Unit: ".$row['myunit']."<br />\n";
        echo "Models: ".$row['mymodels']."<br />\n";
		echo "Prob: ".$row['myprob']."<br />\n";
		echo "Ini: ".$row['myini']."<br />\n";
        $i++;
    }
}
if (count($_POST['B']) > 0)
{
    $i = 0;
    foreach ($_POST['B'] as $row)
    {
		echo "Index $i of B<br />\n";
        echo "Unit: ".$row['myunit']."<br />\n";
        echo "Models: ".$row['mymodels']."<br />\n";
		echo "Prob: ".$row['myprob']."<br />\n";
		echo "Ini: ".$row['myini']."<br />\n";
        $i++;
    }
}