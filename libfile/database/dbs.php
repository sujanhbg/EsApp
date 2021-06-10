<?php

/*
 * It need to define the file location with same format
 */

namespace kring\database;

class dbs {

    public $sql;
    public $exparam;

    function __construct() {
        $sql = $this->sql;
        $exparam = $this->exparam;
    }

    function paginationqq($displayrow = 30, $table, $url, $condition, $fieldName = "ID", $curentpage) {
        $limit = $displayrow;

        $sql = "SELECT $fieldName FROM `$table` $condition";
        $total_pages = $this->num_of_row($sql);

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

        // Get page data
        $query1 = "SELECT * FROM $table LIMIT $start, $limit";
        $result = $this->query($query1);

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
                $fnulr = str_replace("@pg", "$prev", $url);
                $paginate .= "<a class=\"waves-effect\" href='$fnulr'>previous</a> ";
            } else {
                $paginate .= "<a class=\"disabled w3-grey waves-effect\" disabled>previous</a> ";
            }

            // Pages
            if ($lastpage < 7 + ($stages * 2)) { // Not enough pages to breaking it up
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page) {
                        $paginate .= "<a class=\"activebtn waves-effect\">$counter</a> ";
                    } else {
                        $fnulr = str_replace("@pg", "$counter", $url);
                        $paginate .= "<a class=\"waves-effect\" href='$fnulr'>$counter</a> ";
                    }
                }
            } elseif ($lastpage > 5 + ($stages * 2)) { // Enough pages to hide a few?
                // Beginning only hide later pages
                if ($page < 1 + ($stages * 2)) {
                    for ($counter = 1; $counter < 4 + ($stages * 2); $counter++) {
                        if ($counter == $page) {
                            $paginate .= "<a class=\"activebtn \">$counter</a> ";
                        } else {
                            $fnulr = str_replace("@pg", "$counter", $url);
                            $paginate .= "<a class=\"waves-effect\" href='$fnulr'>$counter</a> ";
                        }
                    }
                    $paginate .= "...";
                    $fnulrlast = str_replace("@pg", "$LastPagem1", $url);
                    $fnulrlastpage = str_replace("@pg", "$lastpage", $url);
                    $paginate .= "<a class=\"waves-effect\" href='$fnulrlast'>$LastPagem1</a> ";
                    $paginate .= "<a class=\"waves-effect\" href='$fnulrlastpage'>$lastpage</a> ";
                } // Middle hide some front and some back
                elseif ($lastpage - ($stages * 2) > $page && $page > ($stages * 2)) {
                    $fnulr = str_replace("@pg", "1", $url);
                    $fnulr2 = str_replace("@pg", "2", $url);
                    $paginate .= "<a class=\"waves-effect\" href='$fnulr'>1</a> ";
                    $paginate .= "<a class=\"waves-effect\" href='$fnulr2'>2</a> ";
                    $paginate .= "...";
                    for ($counter = $page - $stages; $counter <= $page + $stages; $counter++) {
                        if ($counter == $page) {
                            $paginate .= "<a class=\"activebtn \">$counter</a> ";
                        } else {
                            $fnulr = str_replace("@pg", "$counter", $url);
                            $paginate .= "<a class=\"waves-effect\" href='$fnulr'>$counter</a> ";
                        }
                    }
                    $paginate .= "...";
                    $fnulrlast = str_replace("@pg", "$LastPagem1", $url);
                    $fnulrlastpage = str_replace("@pg", "$lastpage", $url);
                    $paginate .= "<a class=\"waves-effect\" href='$fnulrlast'>$LastPagem1</a> ";
                    $paginate .= "<a class=\"waves-effect\" href='$fnulrlastpage'>$lastpage</a> ";
                } // End only hide early pages
                else {
                    $fnulr = str_replace("@pg", "1", $url);
                    $fnulr2 = str_replace("@pg", "2", $url);
                    $paginate .= "<a class=\"waves-effect\" href='$fnulr'>1</a> ";
                    $paginate .= "<a class=\"waves-effect\" href='$fnulr2'>2</a> ";
                    $paginate .= "...";
                    for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page) {
                            $paginate .= "<a class=\"activebtn \">$counter</a> ";
                        } else {
                            $fnulr = str_replace("@pg", "$counter", $url);
                            $paginate .= "<a class=\"waves-effect waves-effect\" href='$fnulr'>$counter</a> ";
                        }
                    }
                }
            }

            // Next
            if ($page < $counter - 1) {
                $fnulr = str_replace("@pg", "$next", $url);
                $paginate .= "<a class=\"waves-effect waves-effect\" href='$fnulr'>next</a> ";
            } else {
                $paginate .= "<a class=\"disabled w3-grey\" disabled>next</a> ";
            }

            $paginate .= "</span> ";
        }
        $return = '<div class="w3-row"><div class="w3-col s12 m3 l3">' . '<b>' . $total_pages . ' ' . ucwords(str_replace("_", " ", $this->itemname)) . '</b> </div>' . '<div class="w3-col s12 m9 l9 w3-right-align">';
        // pagination
        $return .= $paginate . "</div></div>";
        // ===========================================================================

        return $return;
    }

    function CheckValid() {
        require_once "xlib/gump/gump.class.php";

        $gump = new GUMP();
        $data = array(
            $_REQUEST['fname'] => $_REQUEST['fval']
        );
        $validated = $gump->is_valid($data, array_intersect_key($this->validationarray, array_flip(array(
            $_REQUEST['fname']
        ))));
        if ($validated === true) {
            $return = "<span style='color:green'><i class='fa fa-check-square' aria-hidden='true'></i>" . " Valid!</span>";
        } else {
            $return = "<span style='color:red'><i class='fa fa-times' aria-hidden='true'></i> ";
            $return .= $validated[0] . "</span>";
        }
        echo $return;
    }

    function get_mendatory($fname) {
        require_once "xlib/gump/gump.class.php";
        $gump = new GUMP();
        $data = array(
            $fname => ""
        );
        $validated = $gump->is_valid($data, array_intersect_key($this->validationarray, array_flip(array(
            $fname
        ))));
        if ($validated === true) {
            $return = "";
        } else {
            $return = "<b class=\"w3-text-red\" title=\"This field id required\">*</b>";
        }
        return $return;
    }

    function get_url() {
        return "?app={$this->app}&opt={$this->opt}&ajax=true&{$this->exparam}";
    }

    function get_urlnoajx() {
        return "?app={$this->app}&opt={$this->opt}&{$this->exparam}";
    }

    function get_header() {
        $ret = "\n\t\t<thead>\n\t\t\t<tr>\n";
        $n = 0;


        foreach (explode(",", $this->DisplayName) as $value) {
            $valuex = explode(",", $this->returndata);
            $tdwidth = $this->tblewidth;
            if (isset($_GET['field']) && $_GET['field'] == $valuex[$n]) {
                if ($_GET['shrt'] == "asc") {
                    $headergenurl = "{$this->get_url()}&shrt=desc&field={$valuex[$n]}";
                    $headericons = "&nbsp;<i class=\"fa fa-caret-up\" aria-hidden=\"true\"></i>";
                } else {
                    $headergenurl = "{$this->get_url()}&shrt=asc&field={$valuex[$n]}";
                    $headericons = "&nbsp;<i class=\"fa fa-caret-down\" aria-hidden=\"true\"></i>";
                }
            } else {
                $headergenurl = "{$this->get_url()}&shrt=asc&field={$valuex[$n]}";
                $headericons = "";
            }

            $ret .= "\t\t\t\t<th class=\"w3-padding\" width=\"{$tdwidth[$valuex[$n]]}%\">\n" . "\t\t\t\t\t<a href=\"javascript:void(0);\" onclick=\"javascript:loadurl('{$headergenurl}','tabledata');\" title=\"Shorting\">" . ucwords(str_replace("_", " ", $value)) . $headericons . "</a> " . "\n\t\t\t\t</th>\n";
            $n++;
        }
        if ($this->nocontrol == true) {
            
        } else {
            $ret .= "\t\t\t\t<th style=\"width:50px;\">&nbsp;</th>\n";
        }
        $ret .= "\t\t\t</tr>\n</thead>\n<tbody>\n";
        return $ret;
    }

    function get_query() {
        if (isset($_GET['field'])) {
            if ($_GET['shrt'] == "asc") {
                $shortby = " ORDER BY " . $this->filtertxt($_GET['field']) . " ASC";
            } else {
                $shortby = " ORDER BY " . $this->filtertxt($_GET['field']) . " DESC";
            }
        } else {
            $shortby = " ORDER BY ID " . " DESC";
        }

        if (!isset($_GET['page'])) {
            $pageno = 1;
            $pgurl = null;
        } else {
            $pageno = $_GET['page'];
            $pgurl = "&page=$pageno";
        }

        if (isset($this->rowperpage)) {
            $disprow = $this->rowperpage;
        } else {
            $disprow = 15;
        }
        $displayfrom = ($pageno * $disprow) - $disprow;

        $ret = $this->datasql . " WHERE " . $this->datasqlwhere . $shortby . " LIMIT  " . $displayfrom . "," . $disprow;

        return $ret;
    }

    function get_search_data() {
        if (strlen($this->viewlink) > 5) {
            if ($this->viewlinkon == "top") {
                $viewurl = "<a class=\"w3-text-blue w3-bar-item w3-button\" href=\"{$this->viewlink}\"><i class=\"fa fa-eye w3-blue\" aria-hidden=\"true\"></i>View</a> ";
            } else {
                $viewurl = "<a class=\"w3-text-blue w3-bar-item w3-button\" href=\"javascript:void(0);\" onclick=\"loadurl('{$this->viewlink}','modalbody2');openmodal2();\"><i class=\"fa fa-eye\" aria-hidden=\"true\"></i>View</a> ";
            }
        } else {
            $viewurl = "";
        }
        if (isset($this->editsql)) {
            $editurl = "  <a class=\"w3-text-orange w3-bar-item w3-button\"  href=\"javascript:void(0);\" onclick=\"loadurl('{$this->get_url()}&sopt=editform&ID=@ID','modalbody2');openmodal2();\"><i class=\"fa fa-pencil-square\" aria-hidden=\"true\"></i>Edit</a> ";
        } else {
            $editurl = null;
        }
        if (isset($this->deletesql)) {
            $deleteurl = "  <a class=\"w3-text-red w3-bar-item w3-button\"  href=\"javascript:void(0);\" onclick=\"loadurl('{$this->get_url()}&sopt=deletethis&ID=@ID','modalbody2');openmodal2();\"><i class=\"fa fa-times\" aria-hidden=\"true\"></i>Delete</a> ";
        } else {
            $deleteurl = null;
        }

        echo "<tr>";
        foreach ($this->query($this->qsql) as $value) {
            foreach (explode(",", trim($this->returndata, ",")) as $fieldname) {
                echo "<td>" . $value[$fieldname] . "</td>";
            }
            echo "<td>";
            echo <<<EORR
                 <div class="w3-dropdown-hover w3-right">
              <i class="fa fa-caret-square-o-down" aria-hidden="true"></i>
              <div class="w3-dropdown-content w3-bar-block w3-border" style="right:0">
            EORR;
            echo str_replace("@ID", $value['ID'], $this->exlink);
            echo str_replace("@ID", $value['ID'], $viewurl) . " " . str_replace("@ID", $value['ID'], $editurl) . "" . str_replace("@ID", $value['ID'], $deleteurl) . "</div></div></td>";
            echo "</tr>\n";
        }
    }

    function get_data() {
        if (isset($this->viewlink)) {
            if ($this->viewlinkon == "top") {
                $viewurl = "<a class=\"w3-text-blue w3-bar-item w3-button\" href=\"{$this->viewlink}\"><i class=\"fa fa-eye w3-text-blue\" aria-hidden=\"true\"></i> View</a> ";
            } else {
                $viewurl = "<a class=\"w3-text-blue w3-bar-item w3-button\" href=\"javascript:void(0);\" onclick=\"loadurl('{$this->viewlink}','modalbody2');openmodal2();\"><i class=\"fa fa-eye\" aria-hidden=\"true\"></i> View</a> ";
            }
        } else {
            $viewurl = "";
        }
        if (isset($this->editsql)) {
            $editurl = "  <a class=\"w3-text-orange w3-bar-item w3-button\"  href=\"javascript:void(0);\" onclick=\"loadurl('{$this->get_url()}&sopt=editform&ID=@ID','modalbody2');openmodal2();\"><i class=\"fa fa-pencil-square\" aria-hidden=\"true\"></i> Edit</a> ";
        } else {
            $editurl = null;
        }
        if (isset($this->deletesql)) {
            $deleteurl = "  <a class=\"w3-text-red w3-bar-item w3-button\"  href=\"javascript:void(0);\" onclick=\"loadurl('{$this->get_url()}&sopt=deletethis&ID=@ID','modelbody');openmodal();\"><i class=\"fa fa-times\" aria-hidden=\"true\"> </i> Delete</a> ";
        } else {
            $deleteurl = null;
        }

        echo "<tr>";

        foreach ($this->query($this->get_query()) as $value) {



            foreach (explode(",", trim($this->returndata, ",")) as $fieldname) {
                echo "<td>" . $value[$fieldname] . "</td>";
            }
            if ($this->nocontrol == true) {
                
            } else {
                echo "<td style=\"text-align:right;\">";
                echo <<<EORR
                     <div class="w3-dropdown-hover w3-right">
                  <i class="fa fa-caret-square-o-down fa-2x w3-text-indigo" aria-hidden="true"></i>
                  <div class="w3-dropdown-content w3-bar-block w3-border " style="right:0;">
                EORR;
                echo str_replace("@ID", $value['ID'], $this->exlink);
                echo str_replace("@ID", $value['ID'], $viewurl) . " "
                . str_replace("@ID", $value['ID'], $editurl) . ""
                . str_replace("@ID", $value['ID'], $deleteurl) . "</div></div>";
                echo "</td>";
            }
            echo "</tr>\n";
        }
    }

    function returndata() {
        if (isset($_GET['page'])) {
            $spage = "&shb={$_GET['page']}";
        } else {
            $spage = null;
        }
        if (isset($_GET['field'])) {
            if ($_GET['shrt'] == "asc") {
                $shurl = "&field=" . $_GET['field'] . "&shrt=asc";
            } else {
                $shurl = "&field=" . $_GET['field'] . "&shrt=desc";
            }
        } else {
            $shurl = "&field=ID&shrt=desc";
        }

        // ------------------------------------------------------------------------------
        if (isset($_GET['ajax'])) {
            echo "<table class=\"tableMobilize w3-table-all\" style=\"border-bottom:2px solid black;\">";
            echo $this->get_header();
            $this->get_data();
            echo "</tbody></table>";
            echo "{$this->paginationqq($this->rowperpage, "{$this->tablename}", "javascript:void(0)' onclick='loadurl(\"{$this->get_url()}{$shurl}&page=@pg\",\"tabledata\")", "WHERE " . $this->datasqlwhere, "ID", "{$spage}")}";
        } else {
            // ------------------------------------------------------------------------------
            echo "<div class=\"\">";

            echo "  <div class=\"w3-row\">";
            echo "         <div class=\"w3-col s12 l4 m4\">";
            echo <<<EOT
                        <button onclick="location.reload();" class="btn-small w3-pink waves-effect waves-light "><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>

            EOT;
            if ($this->newformbtn == "true") {
                echo "<a href=\"javascript:void(0);\" onclick=\"loadurl('{$this->get_url()}&sopt=new', 'modalbody2');openmodal2();\" class=\"btn-small w3-blue waves-effect waves-light \">" . "<i class=\"fa fa-plus\" aria-hidden=\"true\"></i> New</a>";
            }

            $selectform = <<<EOT
                        <select class="browser-default" style="padding:5px;" onchange="loadurl('{$this->get_url()}&sopt=defdata&value='+this.value,'mainbody')">
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                        </select>

            EOT;
            $defcount = $this->rowperpage;
            echo str_replace("value=\"" . $defcount . "\">", "value=\"" . $defcount . "\" selected>", $selectform);

            echo "          </div>";
            echo "         <div class=\"w3-col s12 l8 m8\">";
            echo <<<EOT
                        <input type="search" class="w3-input w3-border" placeholder="search here..."
                            onkeyup="loadurl('{$this->get_url()}&sopt=searchdata&key='+this.value,'tabledata')">

            EOT;
            echo "  </div>";

            echo "</div><br>";

            echo "<div class=\"\" id=\"tabledata\">\n";

            echo "\t<table class=\"tableMobilize w3-table-all\" style=\"border-bottom:2px solid black;\">";
            echo $this->get_header();
            $this->get_data();
            echo "\t</table>";
            echo "{$this->paginationqq($this->rowperpage, "{$this->tablename}", "javascript:void(0)' onclick='loadurl(\"{$this->get_url()}{$shurl}&page=@pg\",\"tabledata\")", "WHERE " . $this->datasqlwhere, "ID", "{$spage}")}";

            echo "</div>";
        }
    }

    function dbsrand() {
        if (isset($_GET['sopt'])) {
            $actions = $_GET['sopt'];
        } else {
            $actions = "index";
        }
        if ($actions == "new") {

            $data['title'] = $this->itemname;
            $data['acurl'] = $this->get_url() . "&sopt=newsave";
            $data['get_url'] = $this->get_url();
            $data['get_url_noajx'] = $this->get_urlnoajx();
            $data['appname'] = $this->app;

            foreach ($this->data as $datavalk) {
                $data[$datavalk] = $this->$datavalk;
            }
            $this->twig($this->newform, $data);
        } elseif ($actions == "newsave") {

            if ($this->addsql) {
                $mysqli = $this->connect();
                if ($mysqli->query($this->addsql)) {
                    echo 1;
                } else {
                    if ($this->sqlerror == true) {
                        echo ("Error description: " . $mysqli->error);
                        echo "<hr>";
                        echo $this->addsql;
                    } else {
                        echo "<div class=\"w3-xlarge w3-text-red w3-center\">" . "<i class=\"fa fa-frown-o fa-2x\" aria-hidden=\"true\"></i> <br>" . "Opps! something went wrong</div>";
                    }
                }
            }
        } elseif ($actions == "CheckValid") {

            echo $this->CheckValid();
        } elseif ($actions == "editform") {

            $data['title'] = $this->itemname;
            $data['acurl'] = $this->get_url() . "&sopt=editsave";
            $data['get_url'] = $this->get_url();
            $data['get_url_noajx'] = $this->get_urlnoajx();

            $data['appname'] = $this->app;
            foreach ($this->data as $datavalk) {
                $data[$datavalk] = $this->$datavalk;
            }
            $this->twig($this->editform, $data);
        } elseif ($actions == "editsave") {

            if ($this->editsql) {
                $mysqli = $this->connect();
                if ($mysqli->query($this->editsql)) {
                    echo 1;
                } else {
                    if ($this->sqlerror == true || $this->conf('dev') == 1) {
                        echo ("Error description: " . $mysqli->error);
                        echo "<hr>";
                        echo $this->editsql;
                    } else {
                        echo "<span class=\"w3-xlarge w3-text-red\">" . "<i class=\"fa fa-frown-o fa-2x\" aria-hidden=\"true\"></i> <br>" . "Opps! something went wrong</span>";
                    }
                }
            }
        } elseif ($actions == "deletethis") {

            echo "<div class=\"w3-center w3-xxlarge w3-text-red\">Are you Sure?<br>" . "<a href=\"javascript:void(0);\" onclick=\"document.getElementById('id01').style.display = 'none';\" class=\"btn-small w3-green waves-effect waves-light \">" . "<i class=\"fa  fa-angle-double-left\" aria-hidden=\"true\"></i> No Go Back !</a> &nbsp;&nbsp;&nbsp;" . "<a href=\"javascript:void(0);\" onclick=\"loadurl('{$this->get_url()}&sopt=deletethisconfirm&ID={$_GET['ID']}', 'modelbody');openmodal();\" class=\"btn-small w3-red waves-effect waves-light \">" . "<i class=\"fa fa-times\" aria-hidden=\"true\"></i> Yes Delete !</a>" . "</div>";
        } elseif ($actions == "deletethisconfirm") {

            echo "Delete Action:<br>";
            if ($this->deletesql) {
                $mysqli = $this->connect();
                if ($mysqli->query($this->deletesql)) {
                    echo <<<EOTT
                                        <script>
                                            document.getElementById('id01').style.display = 'none';
                                            loadurl("{$this->get_url()}&ajax=ture", "tabledata");
                                        </script>
                    EOTT;
                } else {
                    if ($this->sqlerror == true) {
                        echo ("Error description: " . $mysqli->error);
                        echo "<hr>";
                        echo $this->deletesql;
                    } else {
                        echo "<div class=\"w3-xlarge w3-text-red w3-center\">" . "<i class=\"fa fa-frown-o fa-2x\" aria-hidden=\"true\"></i> <br>" . "Opps! something went wrong</div>";
                    }
                }
            }
        } elseif ($actions == "searchdata") {

            if (strlen($_GET['key']) < 1) {
                $this->returndata();
            } else {
                echo "<table class=\"tableMobilize w3-table-all\">";
                echo $this->get_header();
                $this->get_search_data();
                echo "</tbody></table>";
            }
        } elseif ($actions == "defdata") {
            setcookie('defdataview', $_GET['value']);
            echo <<<EOTT
                                <script>
                                    location.reload();
                                </script>
            EOTT;
        } else {

            $this->returndata();
        }

        // print_r($_REQUEST);
        // echo "<hr>" . $this->get_query();
    }

}
