<?php

// Get the file and put its contents in a string
$input_file = fopen($argv[1],"r") or die("Unable to open file");
$input_string = fread($input_file, filesize($argv[1]));

// Recursive function for getting marker positions. Found this nice gem by martijn. tweaked slightly:
// http://www.php.net/manual/en/function.strpos.php#107678
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
$newline_interval = strpos($input_string, "\n"); // Frequency of newlines, mainly to determine vertical path

// Iterate through the array of markers, and save the last path string before running the next loop
for ($k = 0; $k < count($markers) - 1; $k++) {

    // Initialize variables
    $first_marker = $markers[$k];
    $second_marker = $markers[$k + 1];
    $marker_newline_difference = ($first_marker % $newline_interval); // What "distance from the left" is the first marker?

    // If it's the first loop iteration, save the initial input string to the main string variable
    if ($k == 0) {
        $last_string = $input_string;
    }

    // Make the first marker the last known asterisk. Needed for horizontal markers to work
    $last_asterisk_position = $first_marker;

    // Should I go vertical? Check to see if the second marker is on the same line
    // If not, write an asterisk at each newline interval
    // Finish when on the line before the second marker
    if ($second_marker > $first_marker - $marker_newline_difference) {
        for ($i = $first_marker; $i < $second_marker; $i++) {
            if ($i % ($newline_interval + 1) == ($marker_newline_difference - 2) && $i != $first_marker) {
                $output_string = substr_replace($last_string, "*", $i, 1);
                $last_string = $output_string;
                $last_asterisk_position = $i;
            }
        }
    }

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

// Save string to file and close
$output_file = fopen($argv[2], "w");
fwrite($output_file, $output_string);
fclose($output_file);
fclose($input_file);