<!DOCTYPE html>
<!--Az oldal hátterének beállítása.-->
<html lang="hu-hu" style=" background-image: url('bg.png'); background-size: contain; background-repeat:   no-repeat; background-attachment: fixed;  background-size: 100% 100%;">

<head>
    <meta charset="utf-8">
    <title>Szélerőművek</title>
    <link rel="icon" type="image/x-icon" href="icon.png">
    <!--Az oldal megjelenéséhez használt scriptek meghívása.-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <?php if ($viewData['style']) echo '<link rel="stylesheet" type="text/css" href="' . $viewData['style'] . '">'; ?>
</head>

<body>
    <header>
        <!--Fejléc hátterének beállítása és a címek.-->
        <div class="jumbotron jumbotron-fluid text-center" style="margin-bottom:0; background-image: url('h_gb.jpg'); background-size: cover; background-repeat:   no-repeat;">
            <h1 style="text-shadow: 2px 2px 10px #ffffff; background-color:rgba(200, 250, 150, .5);">Szélerőművek.</h1>
            <p style="text-shadow: 2px 2px 10px #ffffff; background-color:rgba(200, 250, 150, .5);">Web-programozás II - MVC alkalmazás - Szélerőművek</p>
        </div>

    </header>
    <!--Reszponzív menüsor létrehozása.-->
    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
        <div class="d-flex w-50 order-0">
            <a class="navbar-brand mr-1" href="#"></a><img src="icon.png" alt="Logo" style="width:40px;"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsingNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <!--GetMenu függvény meghívása, ami az adatbázisban található menü elemeket megjeleníti.-->
        <div class="navbar-collapse collapse justify-content-center order-2" id="collapsingNavbar">
            <?php echo Menu::getMenu($viewData['selectedItems']); ?>
        </div>
        <!--Amennyiben bejelentkezett a felhasznáűló, a menüsor jobb szélén kiírja az adatait.-->
        <span class="navbar-text small text-truncate mt-1 w-50 text-right order-1 order-md-last"><em>
                <?= ($_SESSION['userid'] != 0 && isset($_SESSION['userid'])) ?
                    "Bejelentkezett: " . $_SESSION['userlastname'] . " " . $_SESSION['userfirstname'] . " (" . $_SESSION['username'] . ")." : "" ?>
                <?= ($_SESSION['userlevel'] == '__1') ? " (adminisztátor)" : "" ?>
            </em> </span>
    </nav>
    <div style=" overflow: auto; background-image: url('bg.png'); background-size: contain; background-repeat:   no-repeat; background-attachment: fixed;  background-size: 100% 100%;">
        <!--Szekció az oldalak kinézetének megjelenítéséhez..-->
        <section>
            <?php if ($viewData['render']) include($viewData['render']); ?>
        </section>
    </div>
    <!--Footer megjelenítése.-->
    <div class="jumbotron text-center" style="margin-bottom:0; margin-top:0;  ">
        <footer id="lab">
            &copy; Várhegyi-Miłoś Ádám, Csabai Albert <?= date("Y") ?>
        </footer>
    </div>
</body>

</html>