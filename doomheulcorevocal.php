<?php
//DOOMHEUL CORE v1 : ingame
error_reporting(E_ALL & ~E_NOTICE);
include_once('testcol.php');
function end_of_game(){
	echo"
	<p>Fin de partie !</p>
	<a href='index.php'>Nouvelle partie ?</a>";
}
function get_history(){
	if (isset($_GET["history"])){
		$past=unserialize($_GET["history"]);
		$hist=array();
		for ($x=0;$x<count($past);$x++){
			array_push($hist,$past[$x]);
		}
		return($hist);
	}
	else{
		return -1;
	}
}
function get_players_F(){
	if (isset($_GET["meufs"])){
		return(unserialize($_GET["meufs"]));
	}
	else{
		return -1;
	}
}

function get_players_H(){
	if (isset($_GET["mecs"])){
		return(unserialize($_GET["mecs"]));
	}
	else{
		return -1;
	}
}
function get_round(){
	if (isset($_GET["round"])){
		return($_GET["round"]);
	}
	else{
		return -1;
	}
}
function get_modes(){
	if (isset($_GET["modes"])){
		return(unserialize($_GET["modes"]));
	}
	else{
		return -1;
	}
}
function str_replace_first($search, $replace, $subject) {
	//Permet de remplacer la première occurence d'un pattern dans une str
	//str_replace_first(pattern,remplacant,str)
	
    $pos = strpos($subject, $search);
    if ($pos !== false) {
        return substr_replace($subject, $replace, $pos, strlen($search));
    }
    return $subject;
}
function select_players($genre,$f,$h,$n,$number) {
	//1 mec -- 0 meuf -- 2 none
	if ($genre==1){
		return(array_rand(array_flip($h),$number));
	}
	if ($genre==0){
		return(array_rand(array_flip($f),$number));
	}
	else{
		return(array_rand(array_flip($n),$number));
	}
}
function select_group($taille,$n) {
	$mixed=array_rand(array_flip($n),$taille);
	shuffle($mixed);
	return(implode(", ",$mixed));
}

function populate($phrase,$f,$h,$n){
	$nbN=substr_count($phrase,"*N*");
	$nbF=substr_count($phrase,"*F*");
	$nbH=substr_count($phrase,"*H*");
	$nbG=substr_count($phrase,"*G*");
	$nbX=substr_count($phrase,"*X*");
	$totplayers=count($n);
	$totH=count($h);
	$totF=count($f);
	if ($totplayers<($nbF+$nbH+$nbN+$nbG)){
		return("Vous n'etes pas assez pour jouer sur cette phrase !");
	}
	if ($totH<($nbH)){
		return("Vous n'etes pas assez de mecs pour jouer cette phrase !");
	}
	if ($totF<($nbF)){
		return("Vous n'etes pas assez de meufs pour jouer cette phrase !");
	}
	$selected_F=array();
	$selected_H=array();
	$selected_N=array();
	$copy=$phrase;
	$remaining=$n;
	if ($nbF>0){
		$selected_F=select_players(0,$f,$h,$n,$nbF);
		if ($nbF==1){
			$copy=str_replace_first("*F*",$selected_F,$copy);
		}
		else{
			shuffle($selected_F);
			foreach($selected_F as $val){
				$copy=str_replace_first("*F*",$val,$copy);
			}
		}
		$remaining=array_diff($remaining,array($selected_F));
	}
	if ($nbH>0){
		$selected_H=select_players(1,$f,$h,$n,$nbH);
		if ($nbH==1){
			$copy=str_replace_first("*H*",$selected_H,$copy);
		}
		else{
			shuffle($selected_H);
			foreach($selected_H as $val){
				$copy=str_replace_first("*H*",$val,$copy);
			}
		}
		$remaining=array_diff($remaining,array($selected_H));
	}
	if ($nbN>0){
		
		$selected_N=select_players(2,$f,$h,$remaining,$nbN);
		
		if ($nbN==1){
			$copy=str_replace_first("*N*",$selected_N,$copy);
		}
		else{
			shuffle($selected_N);
			foreach($selected_N as $val){
				$copy=str_replace_first("*N*",$val,$copy);
			}
		}
		$remaining=array_diff($remaining,array($selected_N));
	}
	if ($nbG>0){
				
		
		if ($nbG==1){
			$copy=str_replace_first("*G*",select_group(rand(2,count($remaining)),$remaining),$copy);
		}
		else{
			$copy="Je sais pas encore gérer cette connerie de phrase, mais ca va venir";
		}
	}
	if ($nbX>0){
		$selected_X=rand(1,10);
		if ($nbX=1){
			$copy=str_replace_first("*X*",$selected_X,$copy);
		}
		else{
			for($c=0;$c<nbX;$c++){
				$selected_X=rand(1,10);
				$copy=str_replace_first("*X*",$selected_X,$copy);
			}
		}
	}

	return($copy);
}
function choisir_phrase($mode,$nor,$deb,$cen,$cal){
	$merged=array();
	$hist=get_history();
	$past=array();
	if ($mode[0]==1){
		$merged=$nor;
	}
	if ($mode[1]==1){
		$merged=array_merge($deb, $merged);
	}
	if ($mode[2]==1){
		$merged=array_merge($cen, $merged);
	}
	if ($mode[3]==1){
		$merged=array_merge($cal, $merged);
	}
	$merged=array_unique($merged);
	for ($x=0;$x<count($hist);$x++){
		array_push($past,$merged[$hist[$x]]);
	}
	$uni=array_diff($merged,$past);
	shuffle($uni);
	$next=array_rand(array_flip($uni));
	$key=array_search($next,$merged);
	array_push($hist,$key);
	return(array($next,$hist));
}
$femmes=get_players_F();
$hommes=get_players_H();
$round=get_round();
$mode=get_modes();

if ($femmes==-1 or $hommes==-1 or $round==-1 or $mode==-1){
	header('Location: index.php');
}
if ($round<=0){
	end_of_game();
}
else{
	$nosex=array_merge($femmes,$hommes);
	include_once('content.php');
	$selec=choisir_phrase($mode,$nor,$deb,$cen,$cal);
	$sentence=$selec[0];
	$past=$selec[1];
	$result=populate($sentence,$femmes,$hommes,$nosex);
	print($result);
	print("<br />");
	echo '<script type="text/javascript">
	function readOutLoud(phr){
		 var u = new SpeechSynthesisUtterance();
		 u.text = phr;
		 u.lang = "fr-FR";
		 u.rate = 1.1;
		 speechSynthesis.speak(u);
	}
</script>';
	print("<button onclick='readOutLoud(\"".htmlspecialchars(strip_tags($result), ENT_QUOTES)."\")'>Lire la phrase</button>");
	print("</p>");
	$nquerry="?meufs=".$_GET['meufs']."&mecs=".$_GET['mecs']."&modes=".$_GET['modes']."&round=".(intval($_GET['round'])-1)."&history=".serialize($past);
	print("<a href='doomheulcorevocal.php".$nquerry."'>  >>>  </a>");
	include_once('footer.php');
}