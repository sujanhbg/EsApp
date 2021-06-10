<?php

namespace kring\core;

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

class assets {
    /*
     * Issue is, this class can not be change the css path
     */

    public $appdir;

    function __construct() {
        $this->appdir = __ROOT__;
    }

    function kring() {
        return new Kring();
    }

    function baseurl() {
        return $this->kring()->coreconf('baseurl');
    }

    function css($pr) {
        if ($this->kring()->getApp() == "apps") {
            $dir = $this->appdir . "/apps/" . $this->kring()->coreconf('defaultVersion') . "/views/css";
            $filename = isset($pr[2]) ? $pr[2] : $pr[1];
        } else {
            $dir = $this->appdir . "/" . $this->kring()->getApp() . "/" . $this->kring()->coreconf('defaultVersion') . "/assets/css";
            $fileadd = isset($pr[4]) ? "/" . $pr[4] : null;
            $filename = isset($pr[3]) ? $pr[3] . $fileadd : $pr[2];
        }
        header("Content-type: text/css; charset: UTF-8");
        echo is_file($dir . "/" . $filename) ? file_get_contents($dir . "/" . $filename) : "File " . $dir . "/" . $filename . " is not loaded";
    }

    function jscript($pr) {
        if ($this->kring()->getApp() == "apps") {
            $dir = $this->appdir . "/apps/" . $this->kring()->coreconf('defaultVersion') . "/views/js";
            $filename = isset($pr[2]) ? $pr[2] : $pr[1];
        } else {
            $dir = $this->appdir . "/" . $this->kring()->getApp() . "/" . $this->kring()->coreconf('defaultVersion') . "/assets/js";
            $fileadd = isset($pr[4]) ? "/" . $pr[4] : null;
            $filename = isset($pr[3]) ? $pr[3] . $fileadd : $pr[2];
        }
        header('Content-Type: application/javascript');
        $search = ["{{baseurl}}", "{{ baseurl }}", "{{baseurl }}", "{{ baseurl}}"];
        $paste = [$this->baseurl()];
        $filecontent = is_file($dir . "/" . $filename) ? file_get_contents($dir . "/" . $filename) : "File " . $dir . "/" . $filename . " is not loaded";
        //echo str_replace($search, $paste, $filecontent);
        echo \JShrink\Minifier::minify(str_replace($search, $paste, $filecontent), array('flaggedComments' => false));
    }

    function asset() {
        
    }

}
