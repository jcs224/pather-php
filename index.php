<?php

$input_file = fopen("input.txt","r") or die("Unable to open file");
$input_string = fread($input_file, filesize("input.txt"));

// Recursive function for getting marker positions
function strpos_recursive($haystack, $needle, $offset = 0, &$results = []) {
    $offset = strpos($haystack, $needle, $offset);
    if($offset === false) {
        return $results;
    } else {
        $results[] = $offset;
        return strpos_recursive($haystack, $needle, $offset + 1, $results);
    }
}

// Get marker positions
$markers = strpos_recursive($input_string, "#");

echo "Markers: ";
foreach($markers as $i => $marker) {
    echo $marker;
    if ($i != count($markers) - 1) {
        echo ",";
    }
}
echo "<br>";

// Iterate through the array of markers, and save the last path string before running the next loop
for ($k = 0; $k < count($markers) - 1; $k++) {
    // First marker
    $first_marker = $markers[$k];
    echo "First marker: " . $first_marker . "<br>";
    $second_marker = $markers[$k + 1];
    echo "second marker position: " . $second_marker . "<br>";
    $newline_interval = strpos($input_string, "\n");
    echo "first newline position: " . $newline_interval . "<br>";

    $marker_newline_difference = ($first_marker % $newline_interval);
    echo "start/newline remainder: " . $marker_newline_difference . "<br>";


    if ($k == 0) {
        $last_string = $input_string;
    }
    
    $last_asterisk_position = $first_marker;

// Should I go vertical?
    if ($second_marker > $first_marker - $marker_newline_difference) {
        for ($i = $first_marker; $i < $second_marker; $i++) {
            if ($i % ($newline_interval + 1) == ($marker_newline_difference - 2) && $i != $first_marker) {
                $output_string = substr_replace($last_string, "*", $i, 1);
                $last_string = $output_string;
                $last_asterisk_position = $i;
            }
        }
    }

    echo "downward done!";

// If second marker is left of first marker...
    if ($second_marker > $last_asterisk_position + ($newline_interval - $marker_newline_difference)) {
        for ($i = $second_marker + 1; $i < $last_asterisk_position + $newline_interval + 2; $i++) {
            $output_string = substr_replace($last_string, "*", $i, 1);
            $last_string = $output_string;
        }
    } else {

// If right of first marker...
        for ($i = $last_asterisk_position + 1; $i < $second_marker; $i++) {
            if ($i < $second_marker) {
                $output_string = substr_replace($last_string, "*", $i, 1);
                $last_string = $output_string;
            }
        }
    }
}

$output_file = fopen("output.txt", "w");
fwrite($output_file, $output_string);
fclose($output_file);

fclose($input_file);