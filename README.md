# EsApp
 

###### Essential PHP Liquid Framework, PHP Library Collection and Simple Framework for Enterprise Application Development

### Instalation:

composer require sujanhbg/kring
or
Clone it into your machine

###### Configure Apache
```
DocumentRoot "path/to/project/public"
<Directory "path/to/project/public">
    Options Indexes FollowSymLinks Includes ExecCGI
    Require all granted
	  Options +SymLinksIfOwnerMatch 
		RewriteEngine On
		RewriteCond %{REQUEST_URI} !\.png$ [NC]
		RewriteCond %{REQUEST_URI} !\.jpg$ [NC]
		RewriteRule ^(.*)$ index.php [NC,L]
</Directory>
```
Ingnore .htaccess file for speed up your web application
### Use:
##### Creating Database
You need to connect mysql database server for user authentication. 
first create a database as your defined name
import database.sql file in to your database

open configs/database.php
```php
$db['driver'] = "mysqli";
$db['host'] = "localhost";
$db['user'] = "root";
$db['password'] = "";
$db['database'] = "databasename";
```
#### Your application folde is apps/
apps folder contain dev-master folder which define your current development version.


The dev-master folder assets, controllers, models ans view folder.


First create new controller with model.


Open http://yourapplication/kring

Use user name as Admin, Password as Admin1@1


in left side click create controller, then write your controller name (The default controller name is Home.php) press enter.
This action will create **Yourcontrollername.php** in controllers folder and **Model_yourcontrollername.php** in models folder.

open your controller file

```php
<?php

use kring\core\Controller;

class Home extends Controller {

    private $model;
    public $adminarea;

    function __construct() {
        parent::__construct();
        $this->adminarea = 0;
        $this->model = $this->loadmodel('home');
    }

    function index($pr) {
        //its need to be call from database which is defined by user
        $data['title'] = "BDEnglish4Exam";
        $data['metadesc'] = "BDEnglish4Exam provides Bangladeshi(BD) learners, examinees and teachers of English with perfect model tests for both academic and competitive exams.";
        $data['leveldata'] = $this->model->get_leveldata();
        $this->tg('home/dashboard.html', $data);
    }
    
    }
```

here $this->adminarea=0; define, access level of your application. In you need to your authentication change it to 1 instead if 0. 

## Kring use Twig as templete engine by useing $this->tg('templatefile',$data as arrey). You can also use $this->lv('templatefile.php',$data as array) method for codeignighter style templating.




###### Comming soon