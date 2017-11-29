 <?php

// ROOT DIR
define('ROOT', rtrim(dirname(__FILE__), '\\/'));

// Develoment true
define('DEV', true);
if (DEV) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    ini_set('track_errors', 1);
    ini_set('html_errors', 1);
    error_reporting(E_ALL | E_STRICT | E_NOTICE);
}

// include Router
include '../Router.php';

// router var
$router = new Router();

// get all rows in json format
$router->Route('/get/all',function(){
    include ROOT.'/Db.php';
    $Db = new Db('content');
    $Db->getAll(); 
});


// Basic insert 
$router->Route('/insert',function(){
    include ROOT.'/Db.php';
    $Db = new Db('content');
    $Db->set("
        INSERT INTO snippets (name,title,content) 
        VALUES ('Rambo','3 snippet','This is a content')
    ");
    $Db->close();
    // out
    $site_url = Router::site_url();
    die("Finish .. go to <a href='$site_url'>Home</a>");
});

// Basic update
$router->Route('/update',function(){
    include ROOT.'/Db.php';
    $Db = new Db('content');
    $Db->set("
        UPDATE snippets 
        SET 
            name = 'Texas',
            title = 'Hello World'
        WHERE UID is 2;
    ");
    $Db->close();
    // out
    $site_url = Router::site_url();
    die("Finish .. go to <a href='$site_url'>Home</a>");
});

// basic remove
$router->Route('/remove',function(){
    include ROOT.'/Db.php';
    $Db = new Db('content');
    $Db->set("DELETE FROM snippets WHERE uid IS 2");
    $Db->close();
    // out
    $site_url = Router::site_url();
    die("Finish .. go to <a href='$site_url'>Home</a>");
});


 // basic install
$router->Route('/install',function(){
    if(file_exists(ROOT.'/content.db')){
        echo 'The file already exits';
        exit();
    }else{
        
        $site_url = Router::site_url();

        $db = new SQLite3('content.db');
        //drop the table if already exists
        $db->exec('DROP TABLE IF EXISTS snippets');
        // create table
        $db->exec("
            CREATE TABLE snippets ( 
                'uid' INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
                'name' TEXT UNIQUE,
                'title' TEXT DEFAULT 'no title',
                'description' TEXT DEFAULT 'No description',
                'content' TEXT,
                'date' INTEGER,
                'last_update' INTEGER DEFAULT (strftime('%s', 'now'))
            )"
        );
        $info_snippets = "<p>Table snippets has been created</p>";
        $db->exec("
            INSERT INTO snippets (name,title) 
            VALUES ('block','First snippet')
        ");

        $info_snippets .= "<p>Row blog has been create</p>";
        $info_snippets .= "<p>Finish go to <a href='$site_url'>Home</a></p>";
        echo $info_snippets;
        // close database;
        $db->close();
        exit();
    }
});


 // Home
$router->Route('/',function(){
    if(!file_exists(ROOT.'/content.db')){
        echo 'Install demo <a href="install">Install</a>';
    }else{
        echo 'get all demo <a href="get/all">Get all</a>';
    }
});

// launch router
$router->launch();
