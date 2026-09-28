<?php
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="plugin/bootstrap/css/bootstrap.min.css">
<script src="plugin/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="header-container">
    <div class="header-top-field">
        <?php if (isset($isResponsive) && $isResponsive) { ?>
            <div class="desktop-section">
                <img src="style/images/desktop/header_top_<?=$lang?>.jpg" border=0 />
            </div>
            <div class="mobile-section">
                <img src="style/images/mobile/header_top_<?=$lang?>.jpg" border=0 />
            </div>
        <?php } else { ?>
            <img src="style/images/desktop/header_top_<?=$lang?>.jpg" border=0 />
        <?php } ?>
        <div class="lang-options-btn" onclick="langOptionClick();"></div>
        <div class="desktop-section">
            <div class="lang-options-content">
                <div class="lang-options-item" onclick="setLanguage2('<?= SITE_SESSION_KEY ?>','tc', '<?=$lang?>', true);"><div class="lang-options-text">繁體</div></div>
                <div class="lang-options-item" onclick="setLanguage2('<?= SITE_SESSION_KEY ?>','sc', '<?=$lang?>', true);"><div class="lang-options-text">简体</div></div>
                <div class="lang-options-item" onclick="setLanguage2('<?= SITE_SESSION_KEY ?>','en', '<?=$lang?>', true);"><div class="lang-options-text">English</div></div>
            </div>
        </div>
    </div>
</div>
<div class="header-line">
    <div class="mobile-section">
        <div class="lang-options-content-mobile">
            <div class="lang-options-item" onclick="setLanguage2('<?= SITE_SESSION_KEY ?>','tc', '<?=$lang?>', true);"><div class="lang-options-text">繁體</div></div>
            <div class="lang-options-item" onclick="setLanguage2('<?= SITE_SESSION_KEY ?>','sc', '<?=$lang?>', true);"><div class="lang-options-text">简体</div></div>
            <div class="lang-options-item" onclick="setLanguage2('<?= SITE_SESSION_KEY ?>','en', '<?=$lang?>', true);"><div class="lang-options-text">English</div></div>
        </div>
    </div>
</div>
<script >
    function langOptionClick() {
        var content = document.querySelector('.lang-options-content');
        var mobileContent = document.querySelector('.lang-options-content-mobile');
        if (content.style.display === 'block') {
            content.style.display = 'none';
            mobileContent.style.display = 'none';
        } else {
            content.style.display = 'block';
            mobileContent.style.display = 'block';
        }
    }
</script>
<style>
    .lang-options-btn {
        position: absolute;
        bottom: 0;
        right: 2%;
        border: 0;
        /* border: 1px solid red; */
        width: 6%;
        height: 15%;
    }

    .lang-options-content {
        cursor: pointer;
        display: none;
        position: absolute;
        bottom: 15%;
        right: 2%;
        background-color: #f9f9f9;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
        width: 4.5%;
    }

    .lang-options-text {
        padding: 1.5%;
        font-size: 0.7vw;
    }

    .lang-options-item:hover {
        background-color: #bebebe;
    }
    
    .header-line {
        width: 100%;
        position: relative;
    }

    .lang-options-content-mobile {
        cursor: pointer;
        display: none;
        position: absolute;
        top: 0;
        right: 2%;
        background-color: #f9f9f9;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
        width: 19%;
    }
</style>