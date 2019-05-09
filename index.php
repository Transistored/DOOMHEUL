<?php
// DOOMHEUL CORE v1
function ask_number(){
	print('<form action="index.php" method="get">
		Nombre de joueurs : <input type="number" name="nplayers" /><br />
		<input type="submit" value="OK !">
	</form>');
}
function formulaire_suite($nplay){
	print("ok morray, tu t'apprètes à vivre une expérience hors du commun dont tu ne gardera probablement aucun souvenir !</br>T'es sur d'avoir renseigné le bon nombre de joueurs (tu en as mis ".$nplay.") ? Si c'est pas le cas clique <a href='index.php'>ici</a>");
	print('<form action="index.php?nplayers='.$nplay.'&" method="get">');
	for($i=0;$i<$nplay;$i++){
		echo ("Joueur ".($i+1)." : <input type='text' id='p".($i)."' name='p".($i)."'/> <input type='radio' id='sex".(2*$i)."' name='choixsex".$i."' value='1' /><label for='sex".(2*$i)."'>Homme</label><input type='radio' id='sex".(2*$i+1)."' name='choixsex".$i."' value='0' /><label for='sex".(2*$i+1)."'>Femme</label><br />");	
	}
	echo('
	<fieldset>
  <legend>Modes de jeu</legend>
    <input type="checkbox" id="normal" name="normal" value="1">
    <label for="normal">Normal</label>
    <input type="checkbox" id="debile" name="debile" value="1">
    <label for="debile">Débile</label>
	<input type="checkbox" id="centralien" name="centralien" value="1">
    <label for="centralien">Centralien</label>
	<input type="checkbox" id="caliente" name="caliente" value="1">
    <label for="caliente">Caliente</label>
	<br />
</fieldset>');
	echo("<input type='submit' value='C est parti !' />
	<input id='nplayers' name='nplayers' type='hidden' value='".$nplay."' />
	</form>");
}
function launch_game(){
	$femmes=array();
	$hommes=array();
	for($x=0;$x<$_GET['nplayers'];$x++){
		$var1='choixsex'.$x;
		$var2='p'.$x;
		if ($_GET[$var1]=='0'){
			array_push($femmes,$_GET[$var2]);
		}
		else{
			array_push($hommes,$_GET[$var2]);
		}
		
	}
	$sf=serialize($femmes);
	$sh=serialize($hommes);
	$modes=array(0,0,0,0);
	// array : normal-debile-centralien-caliente
	if (isset($_GET['normal'])){
		$modes[0]=1;
	}
	if (isset($_GET['debile'])){
		$modes[1]=1;
	}
	if (isset($_GET['centralien'])){
		$modes[2]=1;
	}
	if (isset($_GET['caliente'])){
		$modes[3]=1;
	}
	$m=serialize($modes);
	header('Location: doomheulcore.php?meufs='.$sf.'&mecs='.$sh.'&round=40&history=a:0:{}&modes='.$m);
	
}
function formulaire_debut(){
	if (isset($_GET['nplayers'])){
		if ($_GET['nplayers']=='1'){
			print('<h1>Boire seul ? Nan déconne pas</h1>');
			header( "refresh:3;url=index.php" );
		}
		$cond=true;
		if (!isset($_GET['normal']) and !isset($_GET['debile']) and !isset($_GET['centralien']) and !isset($_GET['caliente'])){
			$cond=false;
		}
		for($x=0;$x<$_GET['nplayers'];$x++){
			$var1='choixsex'.$x;
			$var2='p'.$x;
			if (!isset($_GET[$var1]) or !isset($_GET[$var2])){
				$cond=false;
			}
			else{
				if ($_GET[$var1]!='1' and $_GET[$var1]!='0'){
					$cond=false;
				}
			}
		}
		if (!$cond){
			formulaire_suite($_GET['nplayers']);
		}
		else{
			launch_game();
		}
	}
	else{
		ask_number();
	}
}




formulaire_debut();

?>