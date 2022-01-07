<?php
require("abiFunctioonid2.php");
if(isSet($_REQUEST["maakonnadlisamine"])){
    Linn_lisamine($_REQUEST["uuemaakonnakeskus"]);
    header("Location: temperatuur.php");
    exit();
}
if(isSet($_REQUEST["lisaMaakond"])){
    if(!empty(trim($_REQUEST["kuupaev"])))/* && !empty(trim($_REQUEST["maakonnakeskus"]))*/{
    lisaMaakond($_REQUEST["kuupaev"], $_REQUEST["maakonnanimi"], $_REQUEST["temperatuur"]);
    header("Location: temperatuur.php");
    exit();
}

}
if(isSet($_REQUEST["kustutusid"])){
    kustutaMaakond($_REQUEST["kustutusid"]);
}
if(isSet($_REQUEST["muutmine"])){
    muudaMaakond($_REQUEST["muudetudid"], $_REQUEST["maakonnakeskus"],
        $_REQUEST["maakonna_id"], $_REQUEST["temperatuur"]);
}
$ilmatemperatuuri=kysiKaupadeAndmed();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Maakond</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel ="stylesheet" type="text/css" href="fakultetYonih.css">
</head>
<body>
<div class="row"> <!-- делит на 3 стольбца -->
<form action="temperatuur.php">
    <div class="header"><h2>Maakond Temperatuurdid</h2></div>
    <dl>
        <div class="column" style="background-color:#aaa;">      <!-- первый -->
        <dt>kuupaev:</dt>
        <dd><input type="date" name="maakonnakeskus" /></dd>
        </div>
        <div class="column" style="background-color:#aaa;">      <!-- второй -->
        <dt>Maakonad:</dt>
        <dd><?php
            echo looRippMenyy("SELECT id, maakonnakeskus FROM maakondadejaoks",
                "maakonna_id");
            ?>
        </dd>
        </div>
        <div class="column" style="background-color:#aaa;">     <!-- третий -->
        <dt>Temperatuur:</dt>
        <dd><input type="number" name="temperatuur" /></dd>
        </div>
    </dl>
    <br><br><br><br><br><br><br><br><br>
    <input type="submit" name="maakonnalisamine" value="Lisa kaup" />
</div>

    <h2>Linn lisamine</h2>
    <input type="text" name="uuemaakonnakeskus" />
    <input type="submit" name="maakonnadlisamine" value="Lisa grupp" />
</form>
<form action="temperatuur.php">
    <h2>Linnad loetelu</h2>
    <table>
        <tr>
            <th>Haldus             .</th>
            <th>Maakonnakeskus         .</th>
            <th>Maakonnad          .</th>
            <th>Temperatuur</th>
        </tr>
        <?php foreach($ilmatemperatuuri as $kaup): ?>
            <tr>
                <?php if(isSet($_REQUEST["muutmisid"]) &&
                    intval($_REQUEST["muutmisid"])==$kaup->id): ?>
                    <td>
                        <input type="submit" name="muutmine" value="Muuda" />
                        <input type="submit" name="katkestus" value="Katkesta" />
                        <input type="hidden" name="muudetudid" value="<?=$kaup->id ?>" />
                    </td>
                    <td><input type="text" name="maakonnakeskus" value="<?=$kaup->maakonnakeskus ?>" /></td>
                    <td><?php
                        echo looRippMenyy("SELECT id, maakonnakeskus FROM maakondadejaoks",
                            "maakonna_id", $kaup->id);
                        ?></td>
                    <td><input type="text" name="temperatuur" value="<?=$kaup->temperatuur ?>" /></td>

                <?php else: ?>
                    <td><a href="temperatuur.php?kustutusid=<?=$kaup->id ?>"
                           onclick="return confirm('Kas ikka soovid kustutada?')">x</a>
                        <a href="temperatuur.php?muutmisid=<?=$kaup->id ?>">m</a>
                    </td>
                    <td><?=$kaup->maakonnakeskus ?></td>
                    <td><?=$kaup->maakonnanimi ?></td>
                    <td><?=$kaup->temperatuur ?></td>
                <?php endif ?>
            </tr>
        <?php endforeach; ?>
    </table>
</form>

</body>
</html>