<?php
//lisamine oma kasutamine nimi, parooli, ja ab nimi
$yhendus=new mysqli("localhost", "danil2", "123456", "danilakulin");

//$otsisona - otsingularity
function kysiKaupadeAndmed($sorttulp="nimetus", $otsisona=""){
    global $yhendus;
    $lubatudtulbad=array("nimetus", "grupinimi", "hind");
    if(!in_array($sorttulp, $lubatudtulbad)){
        return "lubamatu tulp";
    }
    //addslashes stripslashes lisab langioone kustutamine
    $otsisona=addslashes(stripslashes($otsisona));
    $kask=$yhendus->prepare("SELECT kaubad.id, nimetus, grupinimi, hind
       FROM kaubad, kaubagrupid
       WHERE kaubad.kaubagrupi_id=kaubagrupid.id
        AND (nimetus LIKE '%$otsisona%' OR grupinimi LIKE '%$otsisona%')
       ORDER BY $sorttulp");
    //echo $yhendus->error;
    $kask->bind_result($id, $nimetus, $grupinimi, $hind);
    $kask->execute();
    $hoidla=array();
    while($kask->fetch()){
        $kaup=new stdClass();
        $kaup->id=$id;
        $kaup->nimetus=htmlspecialchars($nimetus);
        $kaup->grupinimi=htmlspecialchars($grupinimi);
        $kaup->hind=$hind;
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
  function lisaGrupp($grupinimi){
     global $yhendus;
     $kask=$yhendus->prepare("INSERT INTO kaubagrupid (grupinimi)
                      VALUES (?)");
     $kask->bind_param("s", $grupinimi);
     $kask->execute();
  }

  function lisaKaup($nimetus, $kaubagrupi_id, $hind){
     global $yhendus;
     $kask=$yhendus->prepare("INSERT INTO
       kaubad (nimetus, kaubagrupi_id, hind)
       VALUES (?, ?, ?)");
     $kask->bind_param("sid", $nimetus, $kaubagrupi_id, $hind);
     $kask->execute();
  }
  //kustuta
  function kustutaKaup($kauba_id){
     global $yhendus;
     $kask=$yhendus->prepare("DELETE FROM kaubad WHERE id=?");
     $kask->bind_param("i", $kauba_id);
     $kask->execute();
  }
  //muudab andmed tabelis
  function muudaKaup($kauba_id, $nimetus, $kaubagrupi_id, $hind){
     global $yhendus;
     $kask=$yhendus->prepare("UPDATE kaubad SET nimetus=?, kaubagrupi_id=?, hind=?
                      WHERE id=?");
     $kask->bind_param("sidi", $nimetus, $kaubagrupi_id, $hind, $kauba_id);
     $kask->execute();
  }


