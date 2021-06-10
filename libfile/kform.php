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
/*
 * It need to define the file location with same format
 */

namespace kring\core;

class kform {

    private $formid;
    private $action;
    private $formatt;

    public function set_formid($formid) {
        $formid = !$formid ? "form1" : $formid;
        $this->formid = $formid;
        return $this;
    }

    function get_formid() {
        return $this->formid ? $this->formid : "0900";
    }

    public function set_action($action) {
        $this->action = $action;
        return $this;
    }

    public function set_formatt($att) {
        $this->formatt = $att;
        return $this;
    }

    public function formstart() {
        return "form id=\"{$this->get_formid()}\" action=\"{$this->action}\" {$this->formatt[0]}=\"{$this->formatt[1]}\"";
    }

}
