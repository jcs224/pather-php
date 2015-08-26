<?php
/**
 * Created by PhpStorm.
 * User: joe
 * Date: 8/25/15
 * Time: 7:23 PM
 */

//$myfile = fopen("testfile.txt", "w");
//$text = "........................\n........................";
//fwrite($myfile, $text);
//fclose($myfile);

$input_file = fopen("input.txt","r") or die("Unable to open file");
$input_string = fread($input_file, filesize("input.txt"));

echo $input_string . "<br>";

$num_markers = substr_count($input_string, "#");
echo "number of markers: " . $num_markers . "<br>";

$start_position = strpos($input_string, "#");
echo "start position: " . $start_position . "<br>";

$start_newline = strpos($input_string, "\n");
echo "first newline position: " . $start_newline . "<br>";

$position_newline_difference = ($start_position % $start_newline);
echo "start/newline remainder: " . $position_newline_difference;

$second_position = strpos($input_string, "#", strpos($input_string, "#") + 1);
echo "second marker position: " . $second_position;

$last_string = $input_string;

for ($i = $start_position; $i < $second_position; $i++) {
    if ($i % ($start_newline + 1) == ($position_newline_difference - 2) && $i != $start_position) {
        $output_string = substr_replace($last_string, "*", $i, 1);
        $last_string = $output_string;
        $last_asterisk_position = $i;
        echo "Last asterisk position: " . $last_asterisk_position;
        echo $output_string . "<br>";
    }
}

echo "downward done!";

for ($i = $last_asterisk_position; $i < $second_position; $i++) {
    if ($i < $second_position) {
        $output_string = substr_replace($last_string, "*", $i, 1);
        $last_string = $output_string;
        echo $output_string . "<br>";
    }
}

$output_file = fopen("output.txt", "w");
fwrite($output_file, $output_string);
fclose($output_file);

fclose($input_file);