<?php 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "voice";
    
    $con = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }


    function GetAllA($sql,$con)
	{		
		$result = mysqli_query($con,$sql);
		$result_array = array();
		while($row = mysqli_fetch_assoc($result))
		{
			$result_array[] = $row;
		}


		return $result_array;
	}

	/*function GetRows($sql,$con)
	{		
		$result = mysqli_query($con,$sql);
		$result_array = array();
		while($row = mysqli_fetch_row($result))
		{
			$result_array[] = $row;
		}
		 		
		return $result_array;
	}*/


	function date_ch($dt)
	{
		if($dt!='0000-00-00' && $dt!='')
		{
			$cn_d=strtotime($dt);

			$fin_dt=date('d-m-Y',$cn_d);

			return $fin_dt;
		}
	}


	function GetRow($sql,$con)
	{
		$result = mysqli_query($con,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row;
	}
	
	function insert_data($data,$table_name,$con)
	{

		$key = array_keys($data);  //get key( column name)

		$value = array_values($data);  //get values (values to be inserted)

		$query ="INSERT INTO $table_name ( ". implode(',' , $key) .") VALUES('". implode("','" , $value) ."')";

		$res=mysqli_query($con,$query);
        
		return($res);
	}


	function insert_datas($data, $table_name, $con) {
		// Prepare SQL query
		$fields = implode(", ", array_keys($data));
		$values = "'" . implode("', '", array_values($data)) . "'";
		$sql = "INSERT INTO $table_name ($fields) VALUES ($values)";
	
		
	
		// Uncomment below after debugging
		// Execute SQL query and handle errors
		$result = mysqli_query($con, $sql);
		return $result ? true : false;
	}

	function insert_data_id($data,$table_name,$con)
	{

		$key = array_keys($data);  //get key( column name)

		$value = array_values($data);  //get values (values to be inserted)

		$query ="INSERT INTO $table_name ( ". implode(',' , $key) .") VALUES('". implode("','" , $value) ."')";

		
		
		$res=mysqli_query($con,$query);

		$last_id = mysqli_insert_id($con);

		return($last_id);
	}

	function update_data($data, $table_name, $where,$con)
	{
	    $cols = array();
	 
	    foreach($data as $key=>$val) {
	        $cols[] = "$key = '$val'";
	    }
	    $query = "UPDATE $table_name SET " . implode(', ', $cols) . " WHERE $where";

	    $res=mysqli_query($con,$query);	    

		return($res);
	}


	function GetOne($sql,$con)
	{
		$result = mysqli_query($con,$sql);
		$row = mysqli_fetch_assoc($result);
		$row = array_values($row);
		return $row[0];
	}
	
	function GetAll($sql,$con)
	{
		$result = mysqli_query($con,$sql);
		$result_array = array();
		while($row = mysqli_fetch_assoc($result))
		{
			$result_array[] = $row;
		}
		return $result_array;
	}

	function GetCol($sql,$con)
	{
		$result = mysqli_query($con,$sql);
		$result_array = array( );
        while ( $row = mysqli_fetch_array( $result ) )
        {
            $result_array[] = $row[0];
        }
        return $result_array;
	}

	function Execute($sql,$con)
	{
		$result = mysqli_query($con,$sql);
		if (!$result) 
		{
			return 0;
		}
		else
		{
			return 1;
		}
	}

	function print_array($array,$exit = false) {
    echo '<pre>';
    print_r($array);
    echo '</pre>';
    if($exit) {
        exit();
    }
}
    
?>