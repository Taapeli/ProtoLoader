<?php

/**
 * Metodeja päivämäärämuunnoksiin
 *
 * @author jm
 */
class DateConv {

    static function fromGed($s) {
        /**
         *  Convert Gedcom Date "3 JAN 1918" to "1918-01-03"
         * 
         * @assert ("3 JAN 1918") == "1918-01-03"
         * @assert ("24 JOU 1610") == "1610-12-24"
         * @assert ("0 0 1889") == "1889-00-00"
         */

        $date = explode(' ', $s, 3);
        if ((sizeof($date) != 3) || (strlen($date[2]) != 4)) {
            echo "Warning: DateConv: Invalid gedcom date \"$s\"";
            return $s;
        }
        // Day, always 2 numbers
        $day_num = $date[0];
        if (strlen($day_num) == 1) {
            $day_num = '0' . $day_num;
        }
        // Month as number
        switch ($date[1]) {
            case "JAN":
            case "TAM":
                $month_num = "01";
                break;
            case "FEB":
            case "HEL":
                $month_num = "02";
                break;
            case "MAR":
                $month_num = "03";
                break;
            case "APR":
            case "HUH":
                $month_num = "04";
                break;
            case "MAY":
            case "TOU":
                $month_num = "05";
                break;
            case "JUN":
            case "KES":
                $month_num = "06";
                break;
            case "JUL":
            case "HEI":
                $month_num = "07";
                break;
            case "AUG":
            case "ELO":
                $month_num = "08";
                break;
            case "SEP":
            case "SYY":
                $month_num = "09";
                break;
            case "OCT":
            case "LOK":
                $month_num = "10";
                break;
            case "NOV":
                $month_num = "11";
                break;
            case "DEC":
            case "JOU":
                $month_num = "12";
                break;
            default;
                $month_num = "00";
        }
        $year_num = $date[2];
        return "${year_num}-${month_num}-${day_num}";
    }

    static function toDisplay($date) {
        /**
         * Convert Date "1918-01-03" to "3.1.1918"
         */
        if (strlen($date) < 10) {
            return $date . "(!)"; // False data
        }
        $a = explode('-', $date, 3);
        if ($a[2] == 0) { // No day
            if ($a[1] == 0) { // No month
                return $a[0];   // Year only 1918
            } else { // Month . Year
                return '?.' . ($a[1] + 0) . '.' . $a[0]; // ?.1.1918
            }
        }
        return ($a[2] + 0) . '.' . ($a[1] + 0) . '.' . $a[0];  // Normal
    }

}
