<div id="article">
    <article class="user-section fullscreen">
        <?php 
            $parent = getArticle("basic", $meta["id_parent"], $lang);
            $userService = new UserService($conn);
            $user = $userService->getUserById($_SESSION['id']);

            if(count($user) <> 1){
                echo "<div class=\"err\">".getMessage("err404")."</div>";
            }else{
        ?>

        <h1><?php echo $parent[0]["title_$lang"]?></h1>


        <div class="user-nav gradientGray">
            <?php include 'section.user.nav.php'; ?>
        </div>
        <div class="user-content">
    	<form method="post" class="userEditForm form" name="userInfoEdit">
            
            
                <h3><?php echo getMessage("usrProfile", $user[0]["login"]); ?></h3>
            
            <div>
                <label><?php printMessage("givenname")?>:</label>
                <input type="text" name="givenname" class="w200" value="<?php echo $user[0]['givenname']; ?>"/>
                <div class="clear"></div>
            </div>
             <div>
                <label><?php printMessage("surname")?>:</label>
                <input type="text" name="surname" class="w200" value="<?php echo $user[0]['surname']; ?>" />
                <div class="clear"></div>
            </div>

            <div class="btnbox">
                <input type="hidden" name="lang" value="<?php echo $lang; ?>" />
                <input type="submit" value="<?php printMessage("save"); ?>" class="btn" />
                <div class="clear"></div>
            </div>
        </form>


        <form method="post" class="form" name="userPassForm">
                <h3><?php echo getMessage("userPassChange"); ?></h3>
            
            <div>
                <label class="req"><em>*</em><?php printMessage("userPassOld")?>:</label>
                <input type="password" name="oldPass" class="w200" />
                <div class="clear"></div>
            </div>
             <div>
                <label class="req"><em>*</em><?php printMessage("userPassNew")?>:</label>
                <input type="password" name="newPass" class="w200" />
                <div class="clear"></div>
            </div>
            <div>
                <label class="req"><em>*</em><?php printMessage("userPassNewConfirm")?>:</label>
                <input type="password" name="newPassConfirm" class="w200"  />
                <div class="clear"></div>
            </div>

            <div class="btnbox">
                <input type="submit" value="<?php printMessage("save"); ?>" class="btn" />
                <div class="clear"></div>
            </div>
        </form>
    <?php } ?>

        </div>
        <div class="clear"></div>
    </article>
</div>