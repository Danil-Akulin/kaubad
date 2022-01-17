<?php
$kaubad=simplexml_load_file("andmed.xml");
// otsing nimetuse järgi

function searchByName($query){
    global $kaubad;
    $result = array();
    foreach ($kaubad->kaup as $kaup) {
        if (substr(strtolower($kaup->nimetus), 0, strlen($query))==
            strtolower($query)){
            array_push($result, $kaup);
        }
    }
    return $result;
}


//andmete lisamine xml-i
if (isset($_POST['submit'])){
    $xmlDoc= new DOMDocument("1.0", "UTF-8");

    $nimi=$_POST['nimi'];
    $hind=$_POST['hind'];
    $aasta=$_POST['aasta'];
    $grupp=$_POST['grupp'];

    //
    $xml_kaubad=$kaubad->addChild('kaup');
    $xml_kaubad->addChild('nimetus', $nimi);
    //add Child ('xml struktuur', $nimi - tekst väli)
    $xml_kaubad->addChild('hind', $hind);
    $xml_kaubad->addChild('vaasta', $aasta);

    $xml_kaubagrupp=$xml_kaubad->addChild('kaubagrupp');
    $xml_kaubagrupp->addChild('grupinimi', $grupp);



    $xmlDoc->loadXML($kaubad->asXML(), LIBXML_NOBLANKS);
    $xmlDoc->preserveWhiteSpace=false;
    $xmlDoc->formatOutput = true;
    $xmlDoc->save('andmed-xml');
    header("refresh: 0;");
}
?>

<!doctype html>
<html>
<head>
    <title>Kazahstan</title>
    <link rel ="stylesheet" type="text/css" href="css/fakultetYonih.css">
</head>
<body>
<input type="button" onclick="history.back();" class="b1" value="Tagasi"/>
<br>
<h1>XML Failist andmed</h1>
<form action="?" method="post">
    <label for="otsing">Otsing</label>
    <input type="text" name="otsing" id="otsing" placeholder="nimetus">
    <input type="submit" value="otsi">
</form>
<?php
if (!empty($_POST['otsing'])){
    $result=searchByName($_POST['otsing']);
    foreach ($result as $kaup){
        echo "<li>".$kaup->nimetus;
        echo ", ".$kaup->vaasta;
        echo ", ".$kaup->kaubagrupp->grupinimi."</li>";

    }
}
?>


<table>
    <tr>
        <th>Kaubanimetus</th>
        <th>Hind</th>
        <th>Väljastamise aasta</th>
        <th>Kaubagrupp</th>
        <th>Kirjeldus</th>

    </tr>
    <?php
    foreach ($kaubad->kaup as $kaup){
        echo "<tr>";
        echo "<td>". ($kaup->nimetus)."</td>";
        echo "<td>". ($kaup->hind)."</td>";
        echo "<td>". ($kaup->vaasta)."</td>";
        echo "<td>". ($kaup->kaubagrupp->grupinimi)."</td>";
        echo "<td>". ($kaup->kaubagrupp->kirjeldus)."</td>";
    }
    ?>
</table>
<h1>Andmete lisamine xml faili sisse</h1>
<form action="" method="post">
    <table border="1">
        <tr>
            <td><label for="nimi">Kauba nimetus</label></td>
            <td><input type="text" id="nimi" name="nimi"</td>
        </tr>
        <tr>
            <td><label for="grupp">Kaubagrupp</label></td>
            <td><input type="text" id="grupp" name="grupp"</td>
        </tr>
        <tr>
            <td><label for="aasta">Väljastamise aasta</label></td>
            <td><input type="text" id="aasta" name="aasta"</td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="lisa" name="submit" id="submit">
            </td>
        </tr>
    </table>
</form>
<h1>RSS - Rich Summary Site / Üks XML andmevormingust</h1>








<h2>RSS uudised lenta.ru lehelt</h2>
<ul>
    <?php
    $feed= simplexml_load_file('https://lenta.ru/rss');
    $linkide_arv=10;
    $loendur=1;
    foreach ($feed->channel->item as $item){
        if ($loendur<=$linkide_arv){
            echo "<li>";
            echo "<a href='$item->link' target='_blank'> $item->title </a>";
            echo "</li>";
            $loendur++;
        }
    }
    ?>
    <h2>RSS uudised mk.ru lehelt</h2>
    <?php
    $feed= simplexml_load_file('https://www.mk.ru/rss/news/index.xml');
    $linkide_arv=10;
    $loendur=1;
    foreach ($feed->channel->item as $item){
        if ($loendur<=$linkide_arv){
            echo "<li>";
            echo "<a href='$item->link' target='_blank'> $item->title </a>";
            echo "</li>";
            $loendur++;
        }
    }
    ?>
    <h2>RSS uudised liga.net lehelt</h2>
    <?php
    $feed= simplexml_load_file('https://www.liga.net/news/sport/rss.xml');
    $linkide_arv=10;
    $loendur=1;
    foreach ($feed->channel->item as $item){
        if ($loendur<=$linkide_arv){
            echo "<li>";
            echo "<a href='$item->link' target='_blank'> $item->title </a>";
            echo "</li>";
            $loendur++;
        }
    }
    ?>

</ul>

</body>
</html>