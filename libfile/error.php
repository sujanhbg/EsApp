<?php

/*
 * The MIT License
 *
 * Copyright 2020 sjnx.
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

use kring\core\kring;
use kring\core\controller;

/**
 * Description of error
 *
 * @author sjnx
 *//*
 * It need to define the file location with same format
 * This can be load error.
 */
class errorhndlr extends controller {

    function er404() {
        $kore = new kring();
        $hostname = $_SERVER['REMOTE_ADDR'];
        if ($hostname == '127.0.0.1') {
            $ipexxcee = "Try Class:{$kore->getClassName()}(){......}<br>
          Try Method: {$kore->getMethod()}(){.......}<br>";
        } else {
            $ipexxcee = "";
        }
        return <<<eotty
<head>
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@600;900&display=swap" rel="stylesheet">
        <style>
        body {
  background-color: #ffcccc;
}

.mainbox {
  background-color: #ffcccc;
  margin: auto;
  height: 600px;
  width: 600px;
  position: relative;
}

  .err {
    color: #990000;
    font-family: 'Nunito Sans', sans-serif;
    font-size: 11rem;
    position:absolute;
    left: 20%;
    top: 8%;
  }

.far {
  position: absolute;
  font-size: 8.5rem;
  left: 42%;
  top: 15%;
  color: #990000;
}

 .err2 {
    color: #990000;
    font-family: 'Nunito Sans', sans-serif;
    font-size: 11rem;
    position:absolute;
    left: 68%;
    top: 8%;
  }

.msg {
    text-align: center;
    font-family: 'Nunito Sans', sans-serif;
    font-size: 1.6rem;
    position:absolute;
    left: 16%;
    top: 45%;
    width: 75%;
  }

a {
  text-decoration: none;
  color: white;
}

a:hover {
  text-decoration: underline;
}
        </style>
  <script src="https://kit.fontawesome.com/4b9ba14b0f.js" crossorigin="anonymous"></script>
</head>
<body>
  <div class="mainbox">
    <div class="err">4</div>
    <i class="far fa-question-circle fa-spin"></i>
    <div class="err2">4</div>
    <div class="msg">Maybe this page moved? Got deleted? Is hiding out in quarantine?
        Never existed in the first place?<p>Let's go <a href="{$kore->coreconf('baseurl')}">home</a> and try from there.</p>
   {$ipexxcee}
   </div>
      </div>

<script src="{$this->baseurl()}/js/page.js"></script>
<script src="{$this->baseurl()}/js/app.js"></script>
eotty;
    }

    function index($pr) {
        $data['title'] = "Kring@PHP";
        return $this->er404();
    }

    function user($pr) {
        return $this->rend(json_encode(['name' => 'Sujan']));
    }

    function error($message, $solution) {
        return <<<eotty
<head>
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@600;900&display=swap" rel="stylesheet">
        <style>
        body {
  background-color: #ffcccc;
}

.mainbox {
  background-color: #ffcccc;
  margin: auto;
  height: 600px;
  width: 800px;
  position: relative;
}

  .err {
    color: #990000;
    font-family: 'Nunito Sans', sans-serif;
    font-size: 11rem;
    position:absolute;
    left: 20%;
    top: 8%;
  }

.far {
  position: absolute;
  font-size: 8.5rem;
  left: 42%;
  top: 15%;
  color: #990000;
}

 .err2 {
    color: #990000;
    font-family: 'Nunito Sans', sans-serif;
    font-size: 11rem;
    position:absolute;
    left: 68%;
    top: 8%;
  }

.msg {
    text-align: center;
    font-family: 'Nunito Sans', sans-serif;
    font-size: 1rem;
    position:absolute;
    left: 16%;
    top: 45%;
    width: 75%;
  }

a {
  text-decoration: none;
  color: white;
}

a:hover {
  text-decoration: underline;
}
        </style>
  <script src="https://kit.fontawesome.com/4b9ba14b0f.js" crossorigin="anonymous"></script>
</head>
<body>
        <h1>Kring@Error</h1>
  <div class="mainbox">
    <div class="msg">
    <h1>{$message}</h1>
    <p>{$solution}</p>
   </div>
      </div>

<script src="{$this->baseurl()}/js/page.js"></script>
<script src="{$this->baseurl()}/js/app.js"></script>
eotty;
    }

}
