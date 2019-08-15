<?php 
//include("includes/connection.php");
require_once( dirname( __FILE__ ) . '/connectionClass.php' );
require_once( dirname( __FILE__ ) . '/index.php');

foreach($_FILES as $file) {
	$imgData = base64_encode(file_get_contents($file['tmp_name']));
	$src = 'data: '.mime_content_type($img_file).';base64,'.$imgData;
	//$uid = time().uniqid(rand());
	$uid = date('YmdHis') . uniqid(rand());
	$output = 'avatar/'.$uid.'.jpg';
	
	file_put_contents($output, file_get_contents($file['tmp_name']));
	
	/* INSERT image into Database */
	
	$sql = sqlInsert("select * from snapshot where pid = '".$_SESSION['pid']."'");
	$numrow = mysqli_num_rows($sql);
	if($numrow >0){
		/* REMOVE the old IMAGE */
		$row	= mysqli_fetch_array($sql);
		$oldimg	= $row['Image'];
		if (file_exists($oldimg)) {
			unlink($oldimg);
		}
		sqlInsert("UPDATE snapshot set Image=(id, Image, pid) where pid='".$_SESSION['pid']."'");
	}else{
		sqlInsert("INSERT INTO snapshot (Image, pid) VALUES (?,?)", array($output, $_SESSION['pid']));
	}
	
	
}

?>