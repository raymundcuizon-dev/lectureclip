<?php
/*
* Developer: Rex Adrivan
* Description: OOP pagination class
*/
 
class Pagination{
function Paginate($values,$per_page){
$total_values = count($values);
 
if(isset($_GET['page'])){
$current_page = $_GET['page'];
}else{
$current_page = 1;
}
$counts = ceil($total_values / $per_page);
$param1 = ($current_page - 1) * $per_page;
$this->data = array_slice($values,$param1,$per_page);
 
for($x=1; $x<= $counts; $x++){
$numbers[] = $x;
}
return $numbers;
}
function fetchResult(){ 
$resultsValues = $this->data;
return $resultsValues;
}
}
 
 
// Sample Usage
 
$pag = new Pagination();
$data = array("Hello","Rex","Prosper","Adrivan","Hehe");
$numbers = $pag->Paginate($data,2);
$result = $pag->fetchResult();
foreach($result as $r){
echo '<div>'.$r.'</div>';
 
}
echo '<a href="nav.php?page=1"><</a>';
foreach($numbers as $num){ 
echo '<a href="nav.php?page='.$num.'">'.$num.'</a>';
}
echo '<a href="">></a>';
 
 
 
?>