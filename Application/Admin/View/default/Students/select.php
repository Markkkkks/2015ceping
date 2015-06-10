<?php
dump(''$var'');
$nj = I("nj_id"); 
if(isset($nj)){ 
$q=mysql_query("select * from organization where father = $nj"); 
while($row=mysql_fetch_array($q)){ 
$select[] = array("id"=>$row['id'],"orgname"=>urlencode($row['orgname'])); 
} 
echo urldecode(json_encode($select)); 
} 