<?php 

$min= isset($_POST['min']) ? $_POST['min'] : 0;
$max = isset($_POST['max']) ? $_POST['max'] : 0;

$x=$max- $min; 
if($x<=10 && $x>5){
 $intv=1;
}else if($x >10 && $x<=50){ 
 $intv=5;
}else if($x>50 && $x<=200){
 $intv=10;
} else if($x<=5){ 
 $intv= 0.5;
}else if($x>200 && $x<=500){
 $intv=25;
}else if($x>500){
 $intv=100;
}else{
 $intv=1;
}   
$km=$min; 
$print_arr=array();
while($km<=$max){ 
 $print_arr[]=number_format($km, 2, '.', ''); 
 $km=$km+$intv; 
} 
print_r(json_encode($print_arr));
?>