<h1 style="max-width: 10%; margin-left:auto; margin-right: auto; font-size:52pt;">Hírek</h2>
    <?php
    if ($_SESSION['userid'] == 0 || !isset($_SESSION['userid'])) {
    ?><h2 style="max-width: 50%; margin-left:auto; margin-right: auto;">A hírek eléréséhez és a kommenteléshez
            kérjük jelentkezzen be!</h2><?php
                                    } else { ?>
        <h2><?= ($viewData['uzenet'] ?? "") ?></h2>
        <h2><?= ($viewData['kommentel-uzenet'] ?? "") ?></h2>
        <h2><?= ($viewData['hirbekuld-uzenet'] ?? "") ?></h2>
        <?php
                                        foreach ($viewData as $hir) {
                                            if (isset($hir['hir'])) { ?>
                <div class="list-group">
                    <ul class="list-group-item list-group-item-action" style="max-width: 60%; margin-left:auto; margin-right: auto; margin-top:  20px; list-style-type: none">
                        <li style="max-width: 600px; margin-left:auto; margin-right: auto; padding-top:  40px">
                            <dl>
                                <dt><em><?php echo $hir['bejelentkezes'] . ' - ' . $hir['datum'] ?></em></dt>
                                <dd><?php echo $hir['hir'] ?></dd>
                            </dl>
                        </li>
                        <?php foreach ($hir['kommentek'] as $komment) { ?>
                            <ul>
                                <li style="max-width: 600px; margin-left:auto; margin-right: auto; margin-top:  20px">
                                    <dl>
                                        <dt><em><?php echo $komment['bejelentkezes'] . ' - ' . $komment['datum'] ?></em>
                                        </dt>
                                        <dd><?php echo $komment['komment'] ?></dd>
                                    </dl>
                                </li>
                            </ul>
                        <?php } ?>
                        <form style="max-width: 600px; margin-left:auto; margin-right: auto; margin-top:  20px" action="<?php SITE_ROOT ?>kommentel" method="post">
                            <textarea name="ujkomment" style="width: 100%; height: 60px; padding: 12px 20px; box-sizing: border-box; border: 2px solid #ccc; border-radius: 4px; background-color: #f8f8f8; font-size: 16px; resize: none;"></textarea>
                            <input name="hirid" type="hidden" value="<?php echo $hir['id'] ?>" />
                            <br>
                            <input class="btn btn-secondary" type="submit" value="Komment küldése">
                        </form>
                    </ul>
                </div>
        <?php }
                                        } ?>
        <div class="list-group" style=" margin-top: 50px;">
            <ul class="list-group-item list-group-item-action" style="max-width: 80%; margin-left:auto; margin-right: auto;">
                <h2 style="max-width: 50%; margin-left:auto; margin-right: auto;">Új hír beküldése</h2>
                <form action="<?php SITE_ROOT ?>hirbekuld" method="post">
                    <li style="max-width: 600px; margin-left:auto; margin-right: auto; margin-top:  40px; list-style-type: none;">
                        <textarea name="ujhir" style="width: 100%; height: 150px; padding: 12px 20px; box-sizing: border-box; border: 2px solid #ccc; border-radius: 4px; background-color: #f8f8f8; font-size: 16px; resize: none;"></textarea>
                    </li>
                    <li style="max-width: 600px; margin-left:auto; margin-right: auto; margin-top:  10px; list-style-type: none;">
                        <input class="btn btn-secondary" type="submit" value="Hír beküldése">
                    </li>
                </form>

        </div>
    <?php } ?>