<?php

namespace kring\core;

use kring\database;

/*
 * Copyright (c) 2020, sjnx
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * * Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */
/*
 * It need to define the file location with same format
 */

class kring {

    public $controllerName;
    public $methodname;
    public $arguments;
    public $appdir;

    function __construct() {
        $this->appdir = __ROOT__;
    }

    function setAppDir($dir) {
        return $dir;
    }

    function getApp() {
        require($this->appdir . "/configs/applications.php");
        $defappfolder = isset($app[$this->getrequestarr()[1]]) ? $app[$this->getrequestarr()[1]] : "apps";
        return $defappfolder;
    }

    function get_dir() {
        return $this->appdir;
    }

    function configfile($filename) {
        if (is_file($this->appdir . "/configs/{$filename}.php")) {
            require($this->appdir . "/configs/{$filename}.php");
        } else {
            exit($filename . " Can not be included;Please Check! the " . $this->appdir . "/configs/{$filename}.php");
        }
    }

    function getapps() {
        if (is_file($this->appdir . "/configs/applications.php")) {
            require($this->appdir . "/configs/applications.php");
            return $app;
        } else {
            exit($filename . " Can not be included;Please Check! the " . $this->appdir . "/configs/{$filename}.php");
        }
    }

    function coreconf($varname) {
        require($this->appdir . "/configs/core_" . $this->getApp() . ".php");
        if (isset($core[$varname])) {
            return $core[$varname];
        } else {
            return false;
        }
    }

    function dbconf($varname) {
        require($this->appdir . "/configs/database.php");
        if (isset($db[$varname])) {
            return $db[$varname];
        } else {
            return false;
        }
    }

    function conf($key) {
        if ($this->coreconf('GetCnfValFromDB') == true) {
            $dval = new \kring\database\dbal();
            return $dval->get_single_result("SELECT value FROM configs WHERE name='{$key}' LIMIT 1;");
        } else {
            return null;
        }
    }

    function getV() {
        return $this->coreconf('defaultVersion');
    }

    function isloggedin() {
        return isset($_SESSION['UsrID']) && isset($_SESSION['UsrName']) && isset($_SESSION['UsrRole']) ? true : false;
    }

    private function get_request() {
        return $_SERVER['REQUEST_URI'];
    }

    private function getrequestarr() {
        return explode("/", $this->get_request());
    }

    public function getClassName() {
        if ($this->getApp() == "apps") {
            if (isset($_GET['app'])) {
                $classname = ucfirst(strtolower($_GET['app']));
            } elseif (isset($this->getrequestarr()[1]) && strlen($this->getrequestarr()[1]) > 1) {
                $classname = ucfirst(strtolower($this->getrequestarr()[1]));
            } else {
                $classname = $this->coreconf('defaultController');
            }
        } else {
            if (isset($_GET['app'])) {
                $classname = ucfirst(strtolower($_GET['app']));
            } elseif (isset($this->getrequestarr()[2]) && strlen($this->getrequestarr()[2]) > 1) {
                $classname = ucfirst(strtolower($this->getrequestarr()[2]));
            } else {
                $classname = $this->coreconf('defaultController');
            }
        }
        //echo $classname;
        //exit();
        return $classname;
    }

    private function getClass() {
        $classname = $this->getClassName();
        if ($classname == "Css" || $classname == "Js" || $classname == "Asset") {
            require_once 'asset.php';
            return new assets();
        } else {

            if (is_file($this->appdir . '/' . $this->getApp() . '/' . $this->getV() . '/controllers/' . $classname . ".php")) {
                require_once $this->appdir . '/' . $this->getApp() . '/' . $this->getV() . '/controllers/' . $classname . ".php";
                return new $classname();
            } elseif (is_file($this->appdir . '/' . $this->getApp() . '/' . $this->getV() . '/ESApp/' . $classname . '/Ctrl_' . $classname . ".php")) {
                require_once $this->appdir . '/' . $this->getApp() . '/' . $this->getV() . '/ESApp/' . $classname . '/Ctrl_' . $classname . ".php";
                return new $classname();
            } else {
                require_once $this->appdir . '/' . $this->getApp() . '/' . $this->getV() . '/controllers/' . "Home.php";
                return new \Home();
            }
        }
    }

    function getAuthClass() {
        if (is_file($this->appdir . '/' . $this->getApp() . '/' . $this->getV() . '/controllers/' . "Auth.php")) {
            require_once $this->appdir . '/' . $this->getApp() . '/' . $this->getV() . '/controllers/' . "Auth.php";
            if (class_exists("Auth")) {
                return true;
            } else {
                require_once 'error.php';
                $err = new \errorhndlr();
                echo $err->error("Class Auth not found on Auth Controller", "Rename or define Class Name 'Auth'");
                return false;
            }
        } else {
            require_once 'error.php';
            $err = new \errorhndlr();
            echo $err->error("Controller Auth.php not found", "Create a Controller with Auth.php name");
            return false;
        }
    }

    public function getMethod() {
        if ($this->getApp() == "apps") {
            if (isset($_GET['opt'])) {
                $classname = strtolower($_GET['opt']);
            } elseif (isset($this->getrequestarr()[2]) && strlen($this->getrequestarr()[2]) > 1) {
                $classname = strtolower($this->getrequestarr()[2]);
            } else {
                $classname = $this->coreconf('defaultMethod');
            }
        } else {
            if (isset($_GET['opt'])) {
                $classname = strtolower($_GET['opt']);
            } elseif (isset($this->getrequestarr()[3]) && strlen($this->getrequestarr()[3]) > 1) {
                $classname = strtolower($this->getrequestarr()[3]);
            } else {
                $classname = $this->coreconf('defaultMethod');
            }
        }
        return $classname;
    }

    public function getparams() {
        $totalobj = count($this->getrequestarr());
        $totalindx = $totalobj - 1;
        $ret = [];

        if ($totalobj > 1) {
            $t = 2;
            while ($t <= $totalindx) {
                $ret[$t] = $this->getrequestarr()[$t];
                $t++;
            }
        }
        return $ret;
    }

    private function ipdtls() {
        //http://ip-api.com/json/{query}?fields=4976639
        if ($this->coreconf('SaveIpDataInFile') == true) {
            $ip = $_SERVER['REMOTE_ADDR'];
            if (!file_exists($this->appdir . "/kdata/ipdata/{$ip}.json")) {
                $url = "http://ip-api.com/json/{$_SERVER['REMOTE_ADDR']}?fields=4976639";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_URL, $url);
                $result = curl_exec($ch);
                $myfile = fopen($this->appdir . "/kdata/ipdata/{$ip}.json", "w") or die("Unable to open file!");
                fwrite($myfile, $result);
                return $result;
            } else {
                return file_get_contents($this->appdir . "/kdata/ipdata/{$ip}.json");
            }
        } else {
            return null;
        }
    }

    private function addstats() {
        if ($this->coreconf('SaveLogInDb') == true) {
            $dbal = new database\dbal();
            $sessionid = session_id();
            $userip = $_SERVER['REMOTE_ADDR'];
            $useragent = $_SERVER['HTTP_USER_AGENT'];
            if (!$dbal->num_of_row("SELECT `ID` FROM `v_sessions` WHERE `session_id`='{$sessionid}'")) {
                $sess = "INSERT INTO `v_sessions`"
                        . "(`ID`, `session_id`, `user_ip`, `date_time`, `user_agent`)"
                        . " VALUES "
                        . "(NULL,'{$sessionid}','{$userip}',NOW(),'{$useragent}')";
                $dbal->query_exc($sess);
            }
            $pageurl = $this->get_request();
            $getpageid = $dbal->get_single_result("SELECT ID FROM v_pages WHERE pageurl='{$pageurl}'");
            $pageid = $getpageid ? $getpageid : $dbal->insert('v_pages', ['pageurl' => $pageurl]);
            $dbal->insert('v_visit', ["session_id" => $sessionid, "page_id" => $pageid]);
        }
    }

    public function get_version() {
        return "Version 1.0.0 (First Version)";
    }

    public function Run() {
        require_once 'error.php';

        //print_r($ipd);
        if ($this->coreconf('accesslimit') == true) {
            $ipd = json_decode($this->ipdtls(), true);
            if (!in_array($ipd['country'], $this->coreconf('AllowedCountry'))) {
                //echo "This Site is not available in your country";
                setcookie('llid', $ipd['country']);
                header("HTTP/1.0 404 Not Found");
                exit();
            }
            if ($this->coreconf('AllowProxy') == false) {
                if ($ipd['proxy'] == 1 || $ipd['proxy'] == true) {
                    setcookie('llid', $ipd['country']);
                    header("HTTP/1.0 404 Not Found");
                    exit();
                }
            }
        }
        $err = new \errorhndlr();
        $method = $this->getMethod();
        if (method_exists($this->getClass(), $method)) {
            if ($this->getClass()->adminarea == 1 && !$this->isloggedin() && $this->coreconf("loginwithDB") == true) {
                if (in_array($method, ['login', 'register', 'index', 'logout'], true)) {
                    if ($this->getAuthClass()) {
                        $auth = new \Auth();
                        $auth->$method($this->getparams());
                    }
                } else {
                    echo $err->index([]);
                }
            } else {
                $pagejs = isset($this->getClass()->pagejs) ? $this->getClass()->pagejs : 0;
                if ($pagejs == 1 && !isset($_GET['fd'])) {
                    $this->getClass()->index($this->getparams());
                } else {
                    $this->getClass()->$method($this->getparams());
                }
            }
            // . "()";
        } elseif ($this->getClassName() == "Css") {
            $this->getClass()->css($this->getparams());
        } elseif ($this->getClassName() == "Js") {
            $this->getClass()->jscript($this->getparams());
        } elseif ($this->getClassName() == "Asset") {
            $this->getClass()->asset();
        } else {

            echo $err->index([]);
        }
        $this->addstats();
        $this->ipdtls();
        //print_r($this->getparams());
    }

}
