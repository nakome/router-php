 <?php

include 'router.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$router = new Router();



// get all rows
$router->Route('/get/all',function(){

    // get database
    $db = '';
    if(file_exists('content.db')){
        $db = new SQLite3('content.db');
    }else{
        die('Database not exists');
    }

    // query
    $result = $db->query('SELECT * FROM snippets');
    
    // json output
    header('content-type: application/json;');
    
    // make loop to get all rows
    $output = array();
    
    /*
    *   SQLITE3_ASSOC to show only rows not numbers and rows
    *   Example:
    *   {
    *       0: 1
    *       uid: 1
    *   }
    *
    */
    while($row =  $result->fetchArray(SQLITE3_ASSOC))
    {
        $output[] = $row;
    }
    
    // if result
    if($result){
        print_r(json_encode(array(
            'status' => true,
            'title' => 'Success',
            'body' => $output
        )));
    }else{
        print_r(json_encode(array(
            'status' => false,
            'title' => 'Error',
            'body' => $db->lastErrorMsg()
        )));
    }
    
    // close database;
    $db->close();
    // out
    exit();
});






$router->Route('/insert',function(){
    // load sqlite
    $db = '';
    if(file_exists('content.db')){
        $db = new SQLite3('content.db');
    }else{
        die('Database not exists');
    }

    // call exec
    $db->exec("
        INSERT INTO snippets (name,title,content) 
        VALUES ('about','3 snippet','This is a content')
    ");
    $db->exec("VACUuM;");
    // close database;
    $db->close();
    // out
    die('Finish ..');
});


$router->Route('/update',function(){
    // load sqlite
    $db = '';
    if(file_exists('content.db')){
        $db = new SQLite3('content.db');
    }else{
        die('Database not exists');
    }

    // call exec
    $db->exec("
        UPDATE snippets 
        SET 
            name = 'Texas',
            title = 'Hello World'
       WHERE UID is 3;
    ");
    // close database;
    $db->close();
    // out
    die('Finish ..');
});


$router->Route('/remove',function(){
    // load sqlite
    $db = '';
    if(file_exists('content.db')){
        $db = new SQLite3('content.db');
    }else{
        die('Database not exists');
    }

    // call exec
    $db->exec("DELETE FROM snippets WHERE uid IS 3");
    // close database;
    $db->close();
    // out
    die('Finish ..');
});



 // go to http://localhost
$router->Route('/install',function(){
    if(file_exists('content.db')){
    
        echo 'The file already exits';
        
        exit();
        
    }else{
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
        )");
        
        $info_snippets = "<p>Table snippets has been created</p>";
        
        $db->exec("
            INSERT INTO snippets (name,title) 
            VALUES ('block','First snippet')
            ");
            
        $info_snippets .= "<p>Row blog has been create</p>";
        
        $info_snippets .= "<p>Finish</p>";
        
        echo $info_snippets;
        
        // close database;
        $db->close();
        exit();
    }
});


 // go to http://localhost
$router->Route('/',function(){
    if(!file_exists('content.db')){
        echo 'Install demo <a href="install">Install</a>';
    }else{
        echo 'get all demo <a href="get/all">Get all</a>';
    }
});


$router->launch();
