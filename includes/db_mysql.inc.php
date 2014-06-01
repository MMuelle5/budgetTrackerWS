<?php
    $g_link = false;
   
    function connect()
    {
        // global $g_link;
        // if( $g_link )
            // return $g_link;
				// $g_link = mysql_connect('localhost', 'root', '') or die('Could not connect to server.' );
// //				$g_link = mysql_connect('localhost', 'root', '') or die('Could not connect to server.' );
				// mysql_select_db('budget_tracker', $g_link) or die('Could not select database.');
        // return $g_link;
        global $mysqli;
		if($mysqli) {
			return $mysqli;
		}
        
         $mysqli = new mysqli('localhost', 'root', '', 'budget_tracker');

	   if(mysqli_connect_errno()) {
	      echo "Connection Failed: " . mysqli_connect_errno();
	      exit();
	   }
		return $mysqli;
    }
    
    function disconnect()
    {
        global $g_link;
        if( $g_link != false )
            mysql_close($g_link);
        $g_link = false;
    }

	
	function query($query_string)
	{
		$this->connect();

		#printf("debug: query = %s<br>n", $query_string);

		$this->query_id = mysql_query($query_string,$this->link_id);
		$this->row   = 0;
		$this->errno = mysql_errno();
		$this->error = mysql_error();
		if (!$this->query_id)
		{
			$this->halt("invalid sql: ".$query_string);
		}

		return $this->query_id;
	}

	function next_record()
	{
		$this->record = mysql_fetch_array($this->query_id);
		$this->row   += 1;
		$this->errno = mysql_errno();
		$this->error = mysql_error();

		$stat = is_array($this->record);
		if (!$stat)
		{
			mysql_free_result($this->query_id);
			$this->query_id = 0;
		}
	
		return $stat;
	}
	
	function result()
	{
		return mysql_result($this->query_id,0,0);
	}
	
	function num_rows()
	{
		return mysql_num_rows($this->query_id);
	}
	
	function close()
	{
		#if($this->query_id)
		#{
		#	mysql_free_result($this->query_id);
		#}
		mysql_close($this->link_id);
	}

?>
