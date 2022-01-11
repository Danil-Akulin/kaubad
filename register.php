<?php
$yhendus = new mysqli("localhost", "danil2", "123456", "danilakulin");
session_start();
$error = $_SESSION['error'] ?? "";
//uue kasutaja lisamine andmetabeli sisse


function puhastaAndmed($data){
    //trim()- eemaldab tühikud
    $data=trim($data);
    //htmlspecialchars - ignoreerib <käsk>
    $data=htmlspecialchars($data);
    //stripslashes - eemaldab \
    $data=stripslashes($data);
    return $data;

}
if(isset($_REQUEST["knimi"])&& isset($_REQUEST["psw"])) {

    $login = puhastaAndmed($_REQUEST["knimi"]);
    $pass = puhastaAndmed($_REQUEST["psw"]);
    $sool = 'vagavagatekst';
    $krypt = crypt($pass, $sool);



//kasutajanimi kontroll

    $kask = $yhendus->prepare("SELECT id, unimi, pas FROM uuskasutajad WHERE unimi=?");
    $kask->bind_param("s", $login);
    $kask->bind_result($id, $kasutajanimi, $parool);
    $kask->execute();
    if ($kask->fetch()) {
        $_SESSION["error"] = "Kasutaja on juba olemas";
        header("Location: $_SERVER[PHP_SELF]");
        $yhendus->close();
        exit();

} else {
    $_SESSION["error"] = " ";
}



    $kask = $yhendus->prepare("INSERT INTO uuskasutajad(unimi, pas, isadmin) VALUES (?,?,?)");
    $kask->bind_param("ssi", $login, $krypt, $_REQUEST["admin"]);
    $kask->execute();
    $_SESSION['unimi'] = $login;
    $_SESSION['admin'] = true;
//header("location: temperatuur.php");
$yhendus->close();
}
?>

<!--CREATE TABLE Uuskasutajad(
id int PRIMARY KEY AUTO_INCREMENT,
unimi varchar(100),
pas varchar(100),
isadmin INT
)-->

<html>
<head>
    <title>Registreerimisvorm</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel ="stylesheet" type="text/css" href="css/logincss.css">
</head>
<body>
<?
    if (isset($_SESSION['unimi'])){
?>
<form action="register.php" method="post" class="modal-content">
    <div class="imgcontainer">
        <img src="img/tallinn.jpg" alt="Avatar" class="avatar">
    </div>
    <h1>Uue kasutaja registreerimine</h1>
    <label for="knimi">Kasutajanimi</label>
    <input type="text" placeholder="Sisesta kasutajanimi"
           name="knimi" id="knimi" required>
    <br>
    <label for="psw">Parool</label>
    <input type="password" placeholder="Sisesta parool"
           name="psw" id="psw" required>
    <br>
    <label for="admin">Kas teha admin?</label>
    <input type="checkbox" name="admin" id="admin" value="1">
    <br>
    <input type="submit" value="Loo kasutaja"  onclick="window.location.href='loginAB2.php'">
    <button type="button"
            onclick="window.location.href='temperatuur.php'"
            class="cancelbtn">Loobu</button>

    <strong> <?=$error ?></strong>

</form>

<?
}
?>
</body>
</html>