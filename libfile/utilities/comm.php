<?php

namespace kring\utilities;

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
/*
 * It need to define the file location with same format
 */

class comm {

    function kring() {
        return new \kring\core\Kring();
    }

    function filtertxt($strings) {
        $search = [
            "'",
            '"',
            "union ",
            "SELECT ",
            "@",
            "-",
            "*",
            "like ",
            "(",
            ")",
            "from ",
            "order "
        ];
        return str_ireplace($search, "", $strings);
    }

    function url_str($string, $mode = 0) {
        $search = array(
            " ",
            "  ",
            "+"
        );
        $rplc = array(
            "_",
            "~",
            "pppp"
        );
        if ($mode == 0) {
            return str_replace($search, $rplc, $string);
        } else {
            return str_replace($rplc, $search, $string);
        }
    }

    function get($varname) {
        return $this->filtertxt($_GET[$varname]);
    }

    function post($varname) {
        return filter_var($_POST[$varname], FILTER_UNSAFE_RAW);
    }

    function rqstr($varname) {
        $search = ["'", '"', "union", "SELECT", "@", "-", "*", "like", "(", ")", "from", "order", "by"];
        $past = "::";
        if (isset($_GET[$varname])) {
            $string = explode("::", str_ireplace($search, $past, $_GET[$varname]));

            // print_r($string);
            $return = $string[0];
        } else {
            if (isset($_REQUEST[$varname])) {
                $return = addslashes($_REQUEST[$varname]);
            } else {
                $return = null;
            }
        }
        return $return;
    }

    function current_dateTime() {
        return date('Y-m-d H:i:s');
    }

    function swiftmail() {
        $transport = (new Swift_SmtpTransport($this->kring()->conf('mail_host')
                        , $this->kring()->conf('mail_port')))
                ->setUsername($this->kring()->conf('mail_username'))
                ->setPassword($this->kring()->conf('mail_password'));
        return new Swift_Mailer($transport);
    }

}
