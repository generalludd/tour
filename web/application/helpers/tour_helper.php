<?php
defined('BASEPATH') or exit('No direct script access allowed');

// tourist_helper.php Chris Dart Mar 22, 2014 3:01:04 PM
// chrisdart@cerebratorium.com
/**
 * update a list of shirt sizes by grouping
 *
 * @param array $shirt_count
 * @param string|null $shirt_size
 */
function update_shirt_count (array &$shirt_count, string $shirt_size = NULL): void {
    if (empty($shirt_size)) {
        $shirt_size = "Unknown";
    }
    if (!empty($shirt_count[$shirt_size])) {
        $shirt_count[$shirt_size] ++;
    } else {
        $shirt_count[$shirt_size] = 1;
    }

}

function sort_shirts ($shirt_list, $format = "inline"): array|string {
    $shirts = array_custom_sort($shirt_list, array(
            "Unknown",
            "S",
            "M",
            "L",
            "XL",
            "XXL",
            "XXXL",
            "XXXXL"
    ));
    switch ($format) {
        case "inline":
            foreach ($shirts as $shirt) {
                $list[] = sprintf("<strong>%s</strong>: %s", $shirt["key"], $shirt["value"]);
            }
            $output = implode(", ", $list);
            break;
        case "table":
            foreach ($shirts as $shirt) {
                $list[] = sprintf("<tr><td>%s</td><td>%s</td></tr>", $shirt["key"], $shirt["value"]);
            }
            $output = sprintf("<table class='list'><thead><tr><th>Size</th><th>Count</th></tr></thead><tbody>%s</tbody></table>", implode("\r", $list));
            break;
        case "list":
            foreach ($shirts as $shirt) {
                $list[] = sprintf("<li>%s: %s</li>", $shirt["key"], $shirt["value"]);
            }
            $output = sprintf("<ul>%s</ul>", implode("\r", $list));
            break;
        default:
            $output = $shirts;
            break;
    }

    return $output;
}
