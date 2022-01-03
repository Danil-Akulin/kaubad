<?php
require("abiFunctioonid.php");
if(isSet($_REQUEST["grupilisamine"])){
    lisaGrupp($_REQUEST["uuegrupinimi"]);
    header("Location: kaubahaldus.php");
    exit();
}
if(isSet($_REQUEST["kaubalisamine"])){
    if(!empty(trim($_REQUEST["nimetus"])))/* && !empty(trim($_REQUEST["nimetus"]))*/{
    lisaKaup($_REQUEST["nimetus"], $_REQUEST["kaubagrupi_id"], $_REQUEST["hind"]);
    header("Location: kaubahaldus.php");
    exit();
}
}
if(isSet($_REQUEST["kustutusid"])){
    kustutaKaup($_REQUEST["kustutusid"]);
}
if(isSet($_REQUEST["muutmine"])){
    muudaKaup($_REQUEST["muudetudid"], $_REQUEST["nimetus"],
        $_REQUEST["kaubagrupi_id"], $_REQUEST["hind"]);
}
$kaubad=kysiKaupadeAndmed();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kaupade leht</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel ="stylesheet" type="text/css" href="fakultetYonihGeev.css">
</head>
<body>
<div class="row"> <!-- делит на 3 стольбца -->
<form action="kaubahaldus.php">
    <div class="header"><h2>Kauba lisamine</h2></div>
    <dl>
        <div class="column" style="background-color:#aaa;">      <!-- первый -->
        <dt>Nimetus:</dt>
        <dd><input type="text" name="nimetus" /></dd>
        </div>
        <div class="column" style="background-color:#aaa;">      <!-- второй -->
        <dt>Kaubagrupp:</dt>
        <dd><?php
            echo looRippMenyy("SELECT id, grupinimi FROM kaubagrupid",
                "kaubagrupi_id");
            ?>
        </dd>
        </div>
        <div class="column" style="background-color:#aaa;">     <!-- третий -->
        <dt>Hind:</dt>
        <dd><input type="text" name="hind" /></dd>
        </div>
    </dl>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <input type="submit" name="kaubalisamine" value="Lisa kaup" />
</div>

    <h2>Grupi lisamine</h2>
    <input type="text" name="uuegrupinimi" />
    <input type="submit" name="grupilisamine" value="Lisa grupp" />
</form>
<form action="kaubahaldus.php">
    <h2>Kaupade loetelu</h2>
    <table>
        <tr>
            <th>Haldus</th>
            <th>Nimetus</th>
            <th>Kaubagrupp</th>
            <th>Hind</th>
        </tr>
        <?php foreach($kaubad as $kaup): ?>
            <tr>
                <?php if(isSet($_REQUEST["muutmisid"]) &&
                    intval($_REQUEST["muutmisid"])==$kaup->id): ?>
                    <td>
                        <input type="submit" name="muutmine" value="Muuda" />
                        <input type="submit" name="katkestus" value="Katkesta" />
                        <input type="hidden" name="muudetudid" value="<?=$kaup->id ?>" />
                    </td>
                    <td><input type="text" name="nimetus" value="<?=$kaup->nimetus ?>" /></td>
                    <td><?php
                        echo looRippMenyy("SELECT id, grupinimi FROM kaubagrupid",
                            "kaubagrupi_id", $kaup->id);
                        ?></td>
                    <td><input type="text" name="hind" value="<?=$kaup->hind ?>" /></td>
                <?php else: ?>
                    <td><a href="kaubahaldus.php?kustutusid=<?=$kaup->id ?>"
                           onclick="return confirm('Kas ikka soovid kustutada?')">x</a>
                        <a href="kaubahaldus.php?muutmisid=<?=$kaup->id ?>">m</a>
                    </td>
                    <td><?=$kaup->nimetus ?></td>
                    <td><?=$kaup->grupinimi ?></td>
                    <td><?=$kaup->hind ?></td>
                <?php endif ?>
            </tr>
        <?php endforeach; ?>
    </table>
</form>

</body>
</html>
