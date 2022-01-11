<?php
//lisamine oma kasutamine nimi, parooli, ja ab nimi
$yhendus=new mysqli("localhost", "danil2", "123456", "danilakulin");

//$otsisona - otsingularity
function kysiKaupadeAndmed($sorttulp="maakonnanimi", $otsisona=""){
    global $yhendus;
    $lubatudtulbad=array("maakonnakeskus", "maakonnanimi", "temperatuur");
    if(!in_array($sorttulp, $lubatudtulbad)){
        return "lubamatu tulp";
    }
    //addslashes stripslashes lisab langioone kustutamine
    $otsisona=addslashes(stripslashes($otsisona));
    $kask=$yhendus->prepare("SELECT ilmatemperatuuri.id, maakonnakeskus, maakonnanimi, temperatuur
       FROM ilmatemperatuuri, maakondadejaoks
       WHERE ilmatemperatuuri.maakonna_id=maakondadejaoks.id
        AND (maakonnakeskus LIKE '%$otsisona%' OR maakonnanimi LIKE '%$otsisona%')
       ORDER BY $sorttulp");
    //echo $yhendus->error;
    $kask->bind_result($id, $maakonnakeskus, $maakonnanimi, $temperatuur);
    $kask->execute();
    $hoidla=array();
    while($kask->fetch()){
        $kaup=new stdClass();
        $kaup->id=$id;
        $kaup->maakonnakeskus=htmlspecialchars($maakonnakeskus);
        $kaup->maakonnanimi=htmlspecialchars($maakonnanimi);
        $kaup->temperatuur=$temperatuur;
        array_push($hoidla, $kaup);
    }
    return $hoidla;
}


// dropdownlist
function looRippMenyy($sqllause, $valikunimi, $valitudid=""){
    global $yhendus;
    $kask=$yhendus->prepare($sqllause);
    $kask->bind_result($id, $sisu);
    $kask->execute();
    $tulemus="<select name='$valikunimi'>";
    while($kask->fetch()){
        $lisand="";
        if($id==$valitudid){$lisand=" selected='selected'";}
        $tulemus.="<option value='$id' $lisand >$sisu</option>";
    }
    $tulemus.="</select>";
    return $tulemus;
}
  //lisab uuekaubagrupi
  function Linn_lisamine($maakonnakeskus){
     global $yhendus;
     $kask=$yhendus->prepare("INSERT INTO maakondadejaoks (maakonnakeskus)
                      VALUES (?)");
     $kask->bind_param("s", $maakonnakeskus);
     $kask->execute();
  }


  function lisaMaakond($kuupaev, $maakonnanimi, $temperatuur){                                 //feafaeffafaefefeafeafafeafafaf
     global $yhendus;
     $kask=$yhendus->prepare("INSERT INTO
       ilmatemperatuuri (kuupaev, maakonnanimi, temperatuur)
       VALUES (?, ?, ?)");
     $kask->bind_param("sid", $kuupaev, $maakonnanimi, $temperatuur);
     $kask->execute();
  }

  //kustuta
  function kustutaMaakond($kauba_id){
     global $yhendus;
     $kask=$yhendus->prepare("DELETE FROM ilmatemperatuuri WHERE id=?");
     $kask->bind_param("i", $kauba_id);
     $kask->execute();
  }
  //muudab andmed tabelis
  function muudaMaakond($kauba_id, $maakonnakeskus, $maakonna_id, $temperatuur){
     global $yhendus;
     $kask=$yhendus->prepare("UPDATE ilmatemperatuuri SET maakonnakeskus=?, maakonna_id=?, temperatuur=?
                      WHERE id=?");
     $kask->bind_param("sidi", $maakonnakeskus, $maakonna_id, $temperatuur);
     $kask->execute();
  }


