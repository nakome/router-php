<?php


class Db {
	
	public function __construct($name = ''){
	    // get database
	    $db = '';
	    if(file_exists($name.'.db')){
	        $db = new SQLite3($name.'.db');
	    }else{
	        die('Database not exists');
	    }
	    $this->database = $db;
	}

	public function query($data = ''){
		$database = $this->database;
		$result = $database->query($data);
		return $result;
	}

	public function getAll(){
		$database = $this->database;
	   // query
	    $result = $this->query('SELECT * FROM snippets');
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
	            'body' => $database->lastErrorMsg()
	        )));
	    }
	    $this->close();
	    return $result;
	}
	public function set($data){
		$database = $this->database;
	    // call exec
	    $database->exec($data);
	    $database->exec("VACUUM;");
	}

	public function close(){
		$database = $this->database;
		return $database->close();
	}
}