<div class="select-lang-container">
    <select class="select-lang-options" onchange="changeChannel(this.value)"
        class="lang-selection lang-desktop-select lang-<?= $lang ?>">
        <option value="1" <?php if ($selected == '1') {
            echo 'selected';
        } ?>>Floor&nbsp;現場&nbsp;现场</option>
        <option value="2" <?php if ($selected == '2') {
            echo 'selected';
        } ?>>English&nbsp;英語&nbsp;英語</option>
        <option value="3" <?php if ($selected == '3') {
            echo 'selected';
        } ?>>Cantonese&nbsp;粵語&nbsp;粤语</option>
        <option value="4" <?php if ($selected == '4') {
            echo 'selected';
        } ?>>Putonghua&nbsp;普通話&nbsp;普通话</option>
    </select>
</div>
<script>
    function changeChannel(channel) {
        <?php if ($toSection == 'plackback') { ?>
            window.location.href = 'playback.php?type=' + channel  + '&t=<?= time() ?>';
        <?php } else { ?>
            window.location.href = 'video.php?type=' + channel + '&t=<?= time() ?>';
        <?php } ?>
    }
</script>