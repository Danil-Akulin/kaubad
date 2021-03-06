<?php
$yhendus = new mysqli("localhost", "danil2", "123456", "danilakulin");
// login vorm Andmebaasis salvestatud kasutajanimega ja prolliga
session_start();
if (isset($_SESSION['tuvastamine'])) {
    header('Location: konkurss.php');
    exit();
}
//kontroll kas login vorm on täidetud?
if (isset($_REQUEST['knimi']) && isset($_REQUEST['psw'])) {
    $login =htmlspecialchars( $_REQUEST['knimi']);
    $pass = htmlspecialchars($_REQUEST['psw']);


    $sool = 'vagavagatekst';
    $krypt = crypt($pass, $sool);
    //kontrolline kas andmebaasis on selline kasutaja
    $kask = $yhendus->prepare("SELECT id, unimi, pas, isadmin FROM uuskasutajad WHERE unimi=?");
    $kask->bind_param("s", $login);
    $kask->bind_result($id, $kasutajanimi, $parool, $onAdmin);
    $kask->execute();

    if ($kask->fetch() && $krypt== $parool) {
        $_SESSION['unimi'] = $login;
        if ($onAdmin==1) {
            $_SESSION['admin']= true;
        }
            header("Location: temperatuur.php");
            $yhendus->close();
            exit();
        }
        echo "kasutaja $login või parool $pass on vale";
        $yhendus->close();
    }

?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <link rel ="stylesheet" type="text/css" href="css/logincss.css">
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <body>

    <form action="loginAB2.php" method="post" class="modal-content">
        <div class="imgcontainer">
            <img src="img/tallinn.jpg" alt="Avatar" class="avatar">
        </div>
        <h1>Login vorm</h1>
        <label for="knimi">Kasutajanimi</label>
        <input type="text" placeholder="Sisesta kasutajanimi"
               name="knimi" id="knimi" required>
        <br>
        <label for="psw">Parool</label>
        <input type="password" placeholder="Sisesta parool"
               name="psw" id="psw" required>
        <br>
        <input type="submit" value="Logi sisse">
    </form>

    </body>
    </html>
