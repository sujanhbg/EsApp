<?php

/*
 * The MIT License
 *
 * Copyright 2021 sjnx.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace kring\database;

/**
 * Description of pagi
 *
 * @author sjnx
 *//*
 * It need to define the file location with same format
 */
class pagi {

    public $url;
    public $totalpage;
    public $displayrow;
    public $currentpage;
    public $fieldname;
    public $itemname;

    function __construct() {
        
    }

    function geturl() {
        $url = $this->url;
        if ($url["type"] == "js") {
            return "javascript:void()\" onclick=\"loadurl('{$url["url"]}','{$url["divid"]}'); ";
        } else {
            return $url["url"];
        }
    }

    function pagi() {
        $limit = $this->displayrow;
        $fieldName = $this->fieldname;
        $curentpage = $this->currentpage;
        $total_pages = $this->totalpage;
        $url = $this->geturl();
        //echo "a href=\"" . $url . "\"----";
        $stages = 3;
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 0;
        }
        if ($page) {
            $start = ($page - 1) * $limit;
        } else {
            $start = 0;
        }


        // Initial page num setup
        if ($page == 0) {
            $page = 1;
        }
        $prev = $page - 1;
        $next = $page + 1;
        $lastpage = ceil($total_pages / $limit);
        $LastPagem1 = $lastpage - 1;

        $paginate = '';
        if ($lastpage > 1) {

            $paginate .= "<span class='pagination'>";
            // Previous
            if ($page > 1) {
                $fnulr = str_replace("@pg", "$prev", "$url");
                $paginate .= "<a class=\"waves-effect\" href=\"$fnulr\">Previous</a> ";
            } else {
                $paginate .= "<a class=\"disabled w3-grey waves-effect\" disabled>Previous</a> ";
            }

            // Pages
            if ($lastpage < 7 + ($stages * 2)) { // Not enough pages to breaking it up
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page) {
                        $paginate .= "<a class=\"activebtn waves-effect\">$counter</a> ";
                    } else {
                        $fnulr = str_replace("@pg", "$counter", "$url");
                        $paginate .= "<a class=\"waves-effect\" href=\"$fnulr\">$counter</a> ";
                    }
                }
            } elseif ($lastpage > 5 + ($stages * 2)) { // Enough pages to hide a few?
                // Beginning only hide later pages
                if ($page < 1 + ($stages * 2)) {
                    for ($counter = 1; $counter < 4 + ($stages * 2); $counter++) {
                        if ($counter == $page) {
                            $paginate .= "<a class=\"activebtn \">$counter</a> ";
                        } else {
                            $fnulr = str_replace("@pg", "$counter", "$url");
                            $paginate .= "<a class=\"waves-effect\" href=\"$fnulr\">$counter</a> ";
                        }
                    }
                    $paginate .= "...";
                    $fnulrlast = str_replace("@pg", "$LastPagem1", "$url");
                    $fnulrlastpage = str_replace("@pg", "$lastpage", "$url");
                    $paginate .= "<a class=\"waves-effect\" href=\"$fnulrlast\">$LastPagem1</a> ";
                    $paginate .= "<a class=\"waves-effect\" href=\"$fnulrlastpage\">$lastpage</a> ";
                } // Middle hide some front and some back
                elseif ($lastpage - ($stages * 2) > $page && $page > ($stages * 2)) {
                    $fnulr = str_replace("@pg", "1", "$url");
                    $fnulr2 = str_replace("@pg", "2", "$url");
                    $paginate .= "<a class=\"waves-effect\" href=\"$fnulr\">1</a> ";
                    $paginate .= "<a class=\"waves-effect\" href=\"$fnulr2\">2</a> ";
                    $paginate .= "...";
                    for ($counter = $page - $stages; $counter <= $page + $stages; $counter++) {
                        if ($counter == $page) {
                            $paginate .= "<a class=\"activebtn \">$counter</a> ";
                        } else {
                            $fnulr = str_replace("@pg", "$counter", "$url");
                            $paginate .= "<a class=\"waves-effect\" href=\"$fnulr\">$counter</a> ";
                        }
                    }
                    $paginate .= "...";
                    $fnulrlast = str_replace("@pg", "$LastPagem1", "$url");
                    $fnulrlastpage = str_replace("@pg", "$lastpage", "$url");
                    $paginate .= "<a class=\"waves-effect\" href=\"$fnulrlast\">$LastPagem1</a> ";
                    $paginate .= "<a class=\"waves-effect\" href=\"$fnulrlastpage\">$lastpage</a> ";
                } // End only hide early pages
                else {
                    $fnulr = str_replace("@pg", "1", "$url");
                    $fnulr2 = str_replace("@pg", "2", "$url");
                    $paginate .= "<a class=\"waves-effect\" href=\"$fnulr\">1</a> ";
                    $paginate .= "<a class=\"waves-effect\" href=\"$fnulr2\">2</a> ";
                    $paginate .= "...";
                    for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page) {
                            $paginate .= "<a class=\"activebtn \">$counter</a> ";
                        } else {
                            $fnulr = str_replace("@pg", "$counter", "$url");
                            $paginate .= "<a class=\"waves-effect waves-effect\" href=\"$fnulr\">$counter</a> ";
                        }
                    }
                }
            }

            // Next
            if ($page < $counter - 1) {
                $fnulr = str_replace("@pg", "$next", "$url");
                $paginate .= "<a class=\"waves-effect waves-effect\" href=\"$fnulr\">Next</a> ";
            } else {
                $paginate .= "<a class=\"disabled w3-grey\" disabled>Next</a> ";
            }

            $paginate .= "</span> ";
        }
        $return = '<div class="w3-row pagicontainer"><div class="w3-col s12 m3 l3">' . '<b>' . $total_pages . ' ' . ucwords(str_replace("_", " ", $this->itemname)) . '</b> </div>'
                . '<div class="w3-col s12 m9 l9 w3-right-align">';
        // pagination
        $return .= $paginate . "</div></div>";
        // ===========================================================================

        return $return;
    }

}
