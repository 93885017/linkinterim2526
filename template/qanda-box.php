<div class="qanda-field">
    <div id="closeBtn" class="close-btn"><img src="style/images/qanda-colse-btn.jpg" border=0 /></div>
    <div>
        <div class="row">
            <div class="col-style col-md-6 qanda-half-left">
                <div class="inut-label"><label class="input-lable-text"><?= $langObj->getText('firstname') ?></label>
                </div>
                <input type="text" class="input-style" id="firstname" name="firstname" value="" />
            </div>

            <div class="col-style col-md-6 qanda-half-right">
                <div class="inut-label"><label class="input-lable-text"><?= $langObj->getText('lastname') ?></label>
                </div>
                <input type="text" class="input-style" id="lastname" name="lastname" value="" />
            </div>
        </div>
        <div class="row" style="padding-top:4%;">
            <div class="col-style col-md-12">
                <div class="inut-label"><label class="input-lable-text"><?= $langObj->getText('companyname') ?></label>
                </div>
                <input type="text" class="input-style" id="companyname" name="companyname" value="" />
            </div>
        </div>
        <div class="row" style="padding-top:4%;">
            <div class="col-style col-md-12">
                <div class="inut-label"><label class="input-lable-text"><?= $langObj->getText('email') ?></label>
                </div>
                <input type="text" class="input-style" id="email" name="email" value="" />
            </div>
        </div>
        <div class="row" style="padding-top:3%;">
            <div class="col-style col-md-12">
                <input type="text" class="input-style" id="question" name="question" value=""
                    placeholder="<?= $langObj->getText('Please enter your question here') ?>" />
            </div>
        </div>
        <div style="padding-top:2%;">
            <div class="qanda-policy-view" onclick="openPolicy()"><u><?= $langObj->getText('Privacy Policy') ?></u>
            </div>
            <div id="qnadSubmit" class="qanda-btn-submit submit-button"><?= $langObj->getText('submit') ?></div>
            <div style="clear:both;"></div>
        </div>
    </div>
</div>
<?php
$csrf_token = $_SESSION['csrf_token'];
?>
<script>
    $(document).ready(function () {
        $("#closeBtn").click(function () {
            $('.qanda-field').hide();
        });

        $('#qandaBtn').click(function () {
            var topValue = $('#main-video').height() - $('.qanda-field').outerHeight();
            $('.qanda-field').css('top', topValue);
            $('.qanda-field').show();
            $('html, body').animate({
                scrollTop: $(".qanda-field").offset().top - 80
            }, 500);
        });

        $('#qnadSubmit').click(function () {
            var firstname = document.getElementById('firstname').value;
            var lastname = document.getElementById('lastname').value;
            var companyname = document.getElementById('companyname').value;
            var email = document.getElementById('email').value;
            var question = document.getElementById('question').value;

            if (firstname == '' || lastname == '' || companyname == '' || email == '' || question == '') {
                messageDisplay('<?= $langObj->getText('fill_all') ?>');
                return;
            }

            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                messageDisplay('<?= $langObj->getText('form_err2') ?>');
                return;
            }

            $.ajax({
                type: "POST",
                url: "submit_qanda.php",
                data: {
                    firstname: firstname,
                    lastname: lastname,
                    companyname: companyname,
                    email: email,
                    question: question,
                    lang: '<?= $lang ?>',
                    section: '<?= $currentSection ?>',
                    csrf_token: '<?= $_SESSION['csrf_token'] ?>'
                },
                success: function (response) {
                    if (response.status == 'success') {
                        messageDisplay2(response.message);
                        $('#firstname').val('');
                        $('#lastname').val('');
                        $('#companyname').val('');
                        $('#email').val('');
                        $('#question').val('');
                    } else {
                        messageDisplay('<?= $langObj->getText('submit_fail') ?>');
                    }
                },
                error: function () {
                    messageDisplay('<?= $langObj->getText('submit_fail') ?>');
                }
            });
        });
    });

    function openPolicy() {
        policyDisplay();
    }
</script>
<style>
    #closeBtn img {
        width: 100%;
    }

    .qanda-field {
        display: none;
        position: absolute;
        background-color: #dfdfdf;
        top: 33%;
        width: 35%;
        left: 32.5%;
        padding: 1%;
        font-size: 1vw;
    }

    .qanda-field .input-style {
        border: 1px solid #000;
        width: 100%;
        padding: 1% 2% 0.5% 2%;
        background-color: #dfdfdf;
        font-size: 0.8vw;
    }

    .qanda-field .input-style::placeholder {
        color: #000;
    }

    .qanda-field .input-lable-text {
        margin-bottom: 0.7%;
        font-size: 0.9vw;
    }

    .close-btn {
        padding: 0;
        position: absolute;
        top: 1%;
        right: 1%;
        width: 2%;
        color: white;
        cursor: pointer;
    }

    .qanda-btn-submit {
        float: right;
        border: 1px;
        border-style: solid;
        border-width: thin;
        border-color: #000;
        padding: 0.5% 1% 0 1%;
        font-size: 0.7vw;
        cursor: pointer;
        margin-top: 1.5%;
    }

    .qanda-policy-view {
        float: left;
        cursor: pointer;
        font-size: 0.7vw;
        margin-top: 2.5%;
    }

    button#qnadSubmit {
        font-size: 0.7vw;
    }

    .qanda-half-left {
        padding-right: 1%;
    }

    .qanda-half-right {
        padding-left: 1%;
    }
</style>