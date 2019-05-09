
<?php
$r1=rand(0,255);
$v1=rand(0,255);
$b1=rand(0,255);
$color=array($r1,$v1,$b1);

function nicecol($col){
	$rs=$col[0];
	$vs=$col[1];
	$bs=$col[2];
	return('rgb('.$rs.', '.$vs.', '.$bs.')');
}
function invert($col){
	$r=$col[0];
	$v=$col[1];
	$b=$col[2];
	$rs=sqrt(255*255-$r*$r);
	$vs=sqrt(255*255-$v*$v);
	$bs=sqrt(255*255-$b*$b);
	return('rgb('.$rs.', '.$vs.', '.$bs.')');
}
print("<head><style>a {text-align:center;} h1 {text-align:center;color:".invert($color).";} h3 {text-align:center;color:".invert($color).";}</style></head><body style='background-color:".nicecol($color)."'><br /></br /></br />");
