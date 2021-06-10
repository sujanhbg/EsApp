<?php

/*
 * Copyright 2017 Sujan.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace kring\core;

/*
 * It need to define the file location with same format
 */

class kringform {

    public $formdata;
    public $tablename;
    public $formwidth;
    public $exopt;
    private $formatt = [];
    public $submiturl;
    public $successmsg;

    function __construct() {
        
    }

    function db() {
        return new \kring\database\dbal();
    }

    function set_att($key, $val) {

        switch ($key) :

            case 'action':
                break;

            case 'method':
                if (!in_array($val, array('post', 'get'))) {
                    return false;
                }
                break;

            case 'enctype':
                if (!in_array($val, array('application/x-www-form-urlencoded', 'multipart/form-data'))) {
                    return false;
                }
                break;

            case 'markup':
                if (!in_array($val, array('html', 'xhtml'))) {
                    return false;
                }
                break;

            case 'class':
            case 'id':
                if (!$this->_check_valid_attr($val)) {
                    return false;
                }
                break;

            case 'novalidate':
            case 'onsubmit':break;
            case 'add_honeypot':
            case 'form_element':
            case 'add_submit':
                if (!is_bool($val)) {
                    return false;
                }
                break;

            case 'add_nonce':
                if (!is_string($val) && !is_bool($val)) {
                    return false;
                }
                break;

            default:
                return false;

        endswitch;

        $this->formatt[$key] = $val;

        return true;
    }

    function getatt() {
        $return = null;
        foreach ($this->formatt as $key => $value) {
            $return .= "{$key}=\"{$value}\" ";
        }

        return $return;
    }

    function get_style($style = "dual") {
        return $style;
    }

    function select_db_input($ID, $value, $text, $tablename, $action, $defalt = "1", $widdth = 200, $extra = null, $xoptiontext = null) {

        $sql = "SELECT $value, $text FROM $tablename  $extra";
//Define SQL Query is Double For make Eassily
        $return = "<select $action name=\"$ID\" class=\"w3-select  w3-border\"> \n";
        if ($xoptiontext) {
            $return .= "<option style=\"cursor:pointer;\" value=\"0\">$xoptiontext</option> \n";
        }

        foreach ($this->db()->query($sql) as $valuee) {
            $ID = $valuee[$value];
            $Nname = $valuee[$text];
            //$actiond=str_replace("this.value","$ID",$action);
            if ($ID == $defalt) {
                $return .= "<option style=\"background-color:#008AB8;color:#99D6EB\" value=\"$ID\" selected>-$Nname</option> \n";
            } else {
                $return .= "<option value=\"$ID\" >-$Nname</option> \n";
            }
        }
        $return .= "</select>";

        return $return;
    }

    private function autocomplate() {
        
    }

    function field_maker($frmt, $value, $opt = null, $c_rqssvalff = null, $r_rqssvalff = null, $newfrm = 0) {
        $c_rqssval = isset($_REQUEST['c']) ? $_REQUEST['c'] : $c_rqssvalff;
        $r_rqssval = isset($_REQUEST['r']) ? $_REQUEST['r'] : $r_rqssvalff;

        $c_rqssvalff = preg_replace("/\r|\n/", "", $c_rqssvalff);
        $action = null; //"loadurl('?ajx=1&opt={$opt}&sopt=formcheckdata&r={$r_rqssval}&c={$c_rqssval}&val='+this.value,'{$r_rqssval}_{$c_rqssval}_msg')";
        //$action="loadurl('?ajx=1&opt={$opt}&sopt=formemup&r={$r_rqssval}&c={$c_rqssval}',this.value,'{$r_rqssval}_{$c_rqssval}')";
        //$value = htmlspecialchars($value);
        if (isset($frmt[2])) {
            $frmt2 = $frmt[2];
        } else {
            $frmt2 = null;
        }
        switch ($frmt2) {
            case "none":
                $return = $value;
                break;
            case "selectdb":

                $return = $this->select_db_input($c_rqssvalff, $frmt[3], $frmt[4], $frmt[5], "onclick=\"$action\" ", $value, $frmt[6], $frmt[7], $frmt[8]);
                break;
            case "select":
                $return = "<select name=\"{$c_rqssvalff}\" onchange=\"$action\" class=\"w3-select  w3-border \" style=\"width:100%;\">";
                foreach (explode('|', $frmt[3]) as $optionvalue) {
                    $optiondata = explode("=", $optionvalue);
                    $return .= "<option value=\"{$optiondata[1]}\">{$optiondata[0]}</option>\n";
                }


                $return .= "</select>";
                break;
            case "selectdb_load":
                $sql = "SELECT {$frmt[3]}, {$frmt[4]} FROM {$frmt[5]}  {$frmt[8]}";
                //Define SQL Query is Double For make Eassily
                $return = "<select $action name=\"{$c_rqssvalff}\" class=\"w3-select  w3-border\" "
                        . "onchange=\"loadurl('{$this->conf('baseurl')}/{$frmt[7]}&value='+this.value,'{$frmt[6]}');\"> \n";
                if ($frmt[6]) {
                    //$return .= "<option style=\"cursor:pointer;\" value=\"0\">$frmt[6]</option> \n";
                }

                foreach ($this->query($sql) as $valuee) {
                    $IDddd = $valuee[$frmt[3]];
                    $Nname = $valuee[$frmt[4]];
                    //$actiond=str_replace("this.value","$ID",$action);
                    if ($IDddd == $frmt[9]) {
                        $return .= "<option style=\"background-color:#008AB8;color:#99D6EB\" value=\"$IDddd\" selected>-$Nname</option> \n";
                    } else {
                        $return .= "<option value=\"$IDddd\" >-$Nname</option> \n";
                    }
                }
                $return .= "</select>";

                break;
            case "selectafter":
                $return = "<select id=\"{$c_rqssvalff}\" class=\"w3-select  w3-border\" "
                        . "onchange=\"loadurl('{$this->conf('baseurl')}/{$frmt[4]}&value='+this.value,'{$frmt[3]}');\""
                        . "><option>---------------------</option></select>";
                break;
            case "div":
                $return = <<<EOT
                            <div  id="capture" style="border:1px solid red;background-color:white;padding:4px" contenteditable>
                            $value
                            </div><br>

                            <div align="right"><a href="javascript:void(0)" id="captureclick" style="font:bold italic 16px verdana;">Save Changes</a></div>

                    <script type="text/javascript">
                        var div = document.getElementById('capture');
                        $('#captureclick').click(function() {
                                    loadurl('?ajx=1&opt={$opt}&sopt=formemup&r={$r_rqssval}&c={$c_rqssval}&val='+div.innerHTML,'{$r_rqssval}_{$c_rqssval}')
                                });
                    </script>

EOT;
                break;
            case "datetime":
                $return = "<input type=\"date\"  name=\"{$c_rqssvalff}\"  value=\"$value\">";
                break;
            case "yn":
                $return = "<select  name=\"{$c_rqssvalff}\"  class=\"w3-select  w3-border \">";
                if (isset($frmt[3])) {

                    foreach ($frmt[3] as $soptkey => $soptval) {

                        if ($value == $soptval) {
                            $return .= "<option value=\"{$soptkey}\" selected>" . $soptval . "</option>";
                        } else {
                            $return .= "<option value=\"{$soptkey}\">" . $soptval . "</option>";
                        }
                    }
                } else {
                    foreach (array("Yes" => 1, "No" => 0) as $valueue => $valuNum) {
                        if ($value == $valuNum) {
                            $return .= "<option value=\"{$valuNum}\" selected>{$valueue}</option>";
                        } else {
                            $return .= "<option value=\"{$valuNum}\">{$valueue}</option>";
                        }
                    }
                }
                $return .= "</select>";
                break;
            case "textarea":
                $textareaid = str_replace(array("\n", " ", "\t"), "", $c_rqssvalff);
                $return = "<textarea id=\"{$textareaid}_t\" class=\"input input-w100 w3-border\" name=\"{$c_rqssvalff}\" >$value</textarea>";
                $return .= <<<EOT
                  <script src="/ck4/ckeditor.js"></script>
                  <script type="text/javascript">

                  CKEDITOR.replace( '{$textareaid}_t',
                  {
                  filebrowserBrowseUrl : '/?app=filem&opt=dashboardck/',
                  filebrowserUploadUrl : '/?app=filem&opt=imguploadfromck/',
                  language: 'bn',
                  uiColor: '#bdc3c7',
                  height:150,
                  });
                  CKEDITOR.instances.{$textareaid}_t.on('blur', function() {
                  });
                  function CKupdate(){
                  for ( instance in CKEDITOR.instances )
                  CKEDITOR.instances[instance].updateElement();
                  }
                  $( "#savenewformdata" ).click(function() {
                  CKupdate();
                  });$( "#saveeditedformdata" ).click(function() {
                  CKupdate();
                  });
                  $( "#{$textareaid}_t" ).change(function() {
                  CKupdate();
                  });
                  </script>

EOT;
                break;
            case "textarea2":
                $textareaid = str_replace(array("\n", " ", "\t"), "", $frmt[0]);
                $return = "<textarea id=\"{$textareaid}_t\" class=\"input input-w100 w3-border\" name=\"{$c_rqssvalff}\" >$value</textarea>";

                break;
            case "readonly":
                $return = "<input class=\"input input-w100 w3-border\" name=\"{$c_rqssvalff}\" type=\"text\"  value=\"$value\" style=\"padding:5px;\" readonly>";
                break;
            case "menual":
                $return = $value;
                break;
            default:
                $return = "<input class=\"input input-w100 w3-border\" name=\"{$c_rqssvalff}\" type=\"{$frmt2}\"  value=\"$value\" onchange=\"$action\"  style=\"padding:5px;\">";
                break;
        }

        return $return . "<span id=\"{$r_rqssval}_{$c_rqssval}_msg\"></span>";
    }

    function forms_new($opt) {
        $formid = rand(4000, 5000);
        $return = "<form class=\"w3-container\" method=\"POST\" action=\"{$this->submiturl}\" "
                . " onsubmit=\"return submitAutoForm(this);snackbar('{$this->successmsg}');\">"
                . "<div class=\"w3-container\" id=\"form_msg\"></div>"
                . "<div class=\"w3-row-padding\">";
        $formwidth = $this->formwidth;
        foreach ($this->formdata as $fieldname) {
            $keynameform = str_replace(array("\n", " "), null, $fieldname[0]);
            $keyname = str_replace(array(" ", "
"), null, $fieldname[0]);
            if (isset($fieldname[3]) && $fieldname['3'] != "") {
                $defvalue = $fieldname[3];
            } else {
                $defvalue = "";
            }
            if ($fieldname[2] == "none") {
                $return .= null;
            } else {
                $lebelname = ucwords(str_ireplace("_", " ", $fieldname[1]));

                $return .= "\n<div class=\"w3-col s12 m{$formwidth[$keynameform]} l{$formwidth[$keynameform]};\""
                        . " style=\"margin-bottom:10px;\">"
                        . "<b><label>{$lebelname} :</label></b>"
                        . "";
                $return .= $this->field_maker($fieldname, $defvalue, $opt, $keyname, "", 1)
                        . "<br></div>\n\n";
            }
        }
        $return .= "</div>"
                . "";
        $return .= "<br><button class=\"btn w3-ripple w3-pink\" style=\"font-size:1.3em;margin-left:16px;\">"
                . "<i class=\"fa fa-floppy-o\" aria-hidden=\"true\"></i> "
                . "&nbsp Save</button><br><br></form>";
        if (isset($this->resultdivid)) {
            $resultdiv = $this->resultdivid;
        } else {
            $resultdiv = "mainbody";
        }
        if (isset($this->resulturl)) {
            $resulturl = $this->resulturl;
        } else {
            $resulturl = "?opt={$opt}";
        }
        if ($resultdiv == "top" || $resultdiv == "body" || $resultdiv == "none") {
            $resdivv = "location.reload();";
        } else {
            $resdivv = "loadurl(\"{$resulturl}\",\"{$resultdiv}\");";
        }

        return $return;
    }

    function forms_update($ID, $opt = "none") {
        $sql = "SELECT * FROM `$this->tablename` WHERE `ID`='$ID' LIMIT 1";
        $ress_arr = $this->db()->query($sql);
        $formid = rand(4000, 5000);
        $formwidth = $this->formwidth;
        $return = "<form class=\"w3-container\" method=\"POST\" action=\"{$this->submiturl}\" "
                . " onsubmit=\"return submitAutoForm(this);\">"
                . "<div class=\"w3-row-padding\">"
                . "\n<input type=\"hidden\" name=\"ID\" value=\"{$ID}\">\n";
        foreach ($ress_arr as $content) {
            foreach ($this->formdata as $fieldname) {
                $keyname = $fieldname[0];
                if ($fieldname[2] == "none") {
                    $return .= null;
                } else {

                    $lebelname = ucwords(str_ireplace("_", " ", $keyname));
                    $return .= "\n<div class=\"w3-col s12 m{$formwidth[$keyname]} l{$formwidth[$keyname]};\""
                            . " style=\"margin-bottom:10px;\">"
                            . "<b><label>{$lebelname} :</label></b><br>"
                            . "";
                    $return .= $this->field_maker($fieldname, $content[$keyname], $opt, $keyname, $ID, 0) . "<br></div>\n\n";
                }
            }
        }


        if (isset($this->resultdivid)) {
            $resultdiv = $this->resultdivid;
        } else {
            $resultdiv = "mainbody";
        }
        if (isset($this->resulturl)) {
            $resulturl = $this->resulturl;
        } else {
            $resulturl = "?opt={$opt}";
        }

        if ($resultdiv == "top" || $resultdiv == "body" || $resultdiv == "none") {
            $resdivv = "location.reload();";
        } else {
            $resdivv = "loadurl(\"{$resulturl}\",\"{$resultdiv}\");";
        }

        $return .= "<a class=\"btn\" id=\"saveeditedformdata\">Save Change</a></div>";
        $return .= "</div>\n\n";
        $return .= "</form>";

        $return .= <<<EOT
<script>

$( "#saveeditedformdata" ).click(function() {
var url = "?ajx=1&opt={$opt}";
$("#formsubmitmsg").show();
$.ajax({
       type: "POST",
       url: url,
       data: $("#{$formid}").serialize(),
       success: function(data)
       {

           if(data==1){
            $("#modelbody").html("<b style='font-size:48px;'>Saved....</b>");
            setTimeout(function(){ $("#id01").fadeOut( "slow" ); }, 3000);
            {$resdivv}
            $(".loader").hide();
          }else{
            $("#modelmsg").html(data);
            $(".loader").hide();
           }

       }
     });
    $("#formsubmitmsg").show();
    $(".loader").show();
    document.getElementById('id01').style.display='block';
    return false;


});

</script>
EOT;
        return $return;
    }

}
