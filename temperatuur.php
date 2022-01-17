<?php
require("abiFunctioonid2.php");
session_start();
/*if(isSet($_REQUEST["tuvastamine"])){
    header("Location: register.php");
    exit();
}*/

if(isSet($_REQUEST["maakonnadlisamine"])){
    Linn_lisamine($_REQUEST["uuemaakonnakeskus"]);
    header("Location: temperatuur.php");
    exit();
}
if(isSet($_REQUEST["lisaMaakond"])){
    if(!empty(trim($_REQUEST["kuupaev"])) && !empty(trim($_REQUEST["temperatuur"]))){
    lisaMaakond($_REQUEST["kuupaev"], $_REQUEST["maakonnakeskus"], $_REQUEST["temperatuur"]);
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
<html lang="et">
<head>
    <title>Maakond</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel ="stylesheet" type="text/css" href="css/fakultetYonih.css">
</head>
<body>
<div id="menuArea">
    <a href="register.php">Loo uus kasutaja</a>
    <?php
    if(isset($_SESSION['unimi'])){
        ?>
        <h1>Tere, <?="$_SESSION[unimi]"?></h1>
        <a href="logout2.php">Logi välja</a>
        <?php
    } else {
        ?>
        <a href="loginAB2.php">Logi sisse</a>
        <?php
    }
    ?>
</div>
<div class="row"> <!-- делит на 3 стольбца -->
<form action="temperatuur.php">
    <div class="header"><h2>Maakond Temperatuurdid</h2></div>
    <dl>
        <div class="column" style="background-color:#aaa;">      <!-- первый -->
        <dt>kuupaev:</dt>
        <dd><label>
                <input type="date" name="kuupaev" />
            </label></dd>
        </div>
        <div class="column" style="background-color:#aaa;">      <!-- второй -->
        <dt>Maakonnakeskus:</dt>
        <dd><?php
            echo looRippMenyy("SELECT id, maakonnakeskus FROM maakondadejaoks",
                "maakonna_id");
            ?>
        </dd>
        </div>
        <div class="column" style="background-color:#aaa;">     <!-- третий -->
        <dt>Temperatuur:</dt>
        <dd><label>
                <input type="number" name="temperatuur" />
            </label></dd>
        </div>
    </dl>
    <br><br><br><br><br><br><br><br><br>
    <input type="submit" name="maakonnalisamine" value="Lisa kaup" />
</div>
<div class="column">
    <h2>Linn lisamine</h2>
    <label>
        <input type="text" name="uuemaakonnakeskus" />
    </label>
    <input type="submit" name="maakonnadlisamine" value="Lisa grupp" />
    <form action="temperatuur.php">
    <h2>Linnad loetelu</h2>

    <table>
        <tr>
            <th>Haldus             .</th>
            <th>Maakonnakeskus         .</th>
            <th>Maakonnad          .</th>
            <th>Temperatuur</th>
        </tr>

        <div id="NewEra">
            <a href="uudised.php">Uudised</a>
        </div>
        <?php foreach($ilmatemperatuuri as $kaup): ?>
            <tr>
                <?php if(isSet($_REQUEST["muutmisid"]) &&
                    intval($_REQUEST["muutmisid"])==$kaup->id): ?>
                    <td>
                        <input type="submit" name="muutmine" value="Muuda" />
                        <input type="submit" name="katkestus" value="Katkesta" />
                        <input type="hidden" name="muudetudid" value="<?=$kaup->id ?>" />
                    </td>
                    <td><label>
                            <input type="text" name="maakonnakeskus" value="<?=$kaup->maakonnakeskus ?>" />
                        </label></td>
                    <td><?php
                        echo looRippMenyy("SELECT id, maakonnakeskus FROM maakondadejaoks",
                            "maakonna_id", $kaup->id);
                        ?></td>
                    <td><label>
                            <input type="text" name="temperatuur" value="<?=$kaup->temperatuur ?>" />
                        </label></td>

                <?php else: ?>
                <td>
                    <?php
                    if(isset($_SESSION['unimi'])){
                    ?>
                    <td><a href="temperatuur.php?kustutusid=<?=$kaup->id ?>"
                           onclick="return confirm('Kas ikka soovid kustutada?')">x</a>
                        <a href="temperatuur.php?muutmisid=<?=$kaup->id ?>">m</a>
                    </td>
                <?php } ?>
                    <td><?=$kaup->maakonnakeskus ?></td>
                    <td><?=$kaup->maakonnanimi ?></td>
                    <td><?=$kaup->temperatuur ?></td>
                <?php endif ?>

            </tr>
        <?php endforeach; ?>
    </table>
</form>
</div>
</body>
</html>