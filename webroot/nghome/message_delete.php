<?php
include("connection.php");
if(isset($_REQUEST['id']))
{
	$id=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));
}
$result = array();
$arr=explode(',',$id);
for($i=0;$i<sizeof($arr);$i++)
{
	deleteMsg($arr[$i]);
}
function deleteMsg($id)
{
	global $result,$conn;
	$sql="DELETE FROM `gym_message` WHERE `id`= $id";
	if($conn->query($sql)) 
	{
		$result['status']='1';
		$result['error']='';
	} 
	else
	{
		$result['status']='0';
		$result['error']='Something getting wrong!!';
	}
}
echo json_encode($result);
$conn->close();

?>