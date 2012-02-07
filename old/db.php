<?class Database {
	public $file_name = "";
	public $conn;
	public function __Construct($file_name){
	$this->file_name = $file_name;
	$this->conn= new PDO("mysql:host=localhost;dbname=sms_gateway",
	'root',
	'insecure_pass');
	$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sql = "Select * from corkboards";
		try {
		$stmt = $this->conn->prepare($sql);
		}
		catch(PDOException $e)
		{
		//Table doesn't exist.
		$create_sql = "create table corkboards(id varchar(100), workspace_json varchar(1000), rubbish_json varchar(1000), primary key(id))";
		$stmt = $this->conn->prepare($create_sql );
		$stmt->execute();
		}
	}
	
	public function login($emailaddress, $password)
	{
		$shapassword = sha1($password . "4e12ed4f76a4b8.53565660");
		
	}
	
	public function id_exists($id){
	$conn = $this->conn;
	$stmt = $conn->prepare('select id from corkboards where id=:id');
	$stmt->bindParam(':id',$id,PDO::PARAM_STR);
	$stmt->execute();
	$result = $stmt->fetchAll();
	foreach($result as $row)
	return true;
	}
	public function get_json($id){
	$conn = $this->conn;
	$stmt = $conn->prepare('select * from corkboards where id=:id');
	$stmt->bindParam(':id',$id,PDO::PARAM_STR);
	$stmt->execute();
	$result = $stmt->fetchAll();
	return $result;
	}
	public function save_data($id, $workspace_json, $rubbish_json){
	$conn = $this->conn;
	$stmt = $conn->prepare('update corkboards set workspace_json = :workspace_json, rubbish_json = :rubbish_json where id=:id');
	$stmt->bindParam(':id',$id,PDO::PARAM_STR);
	$stmt->bindParam(':workspace_json',$workspace_json,PDO::PARAM_STR);
	$stmt->bindParam(':rubbish_json',$rubbish_json,PDO::PARAM_STR);
	$result = $stmt->execute();
	echo "SAVED";
	return $result;	
	}
	public function new_id(){
	$result = false;
	
	$conn = $this->conn;
		while($result == false){
		$id = Helper::random_string(5);
		$stmt = $conn->prepare('insert into corkboards (id) VALUES (:id)');
		$stmt->bindParam(':id',$id,PDO::PARAM_STR);
			try
			{
			$result = $stmt->execute();
			}
			catch(PDOException $e)
			{
			
			}
		}
	return $id;
	}

}