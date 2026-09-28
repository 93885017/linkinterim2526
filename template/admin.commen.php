<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Menu</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="qanda_list.php">Q&A listing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="set_page.php">Set Campaign Page</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0);" onclick="exportData('register')" >Export Register record</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0);" onclick="exportData('qanda')" >Export Q&A record</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<br>
<script>
    function exportData(type) {
        var t = new Date().getTime();
        if (type === 'qanda') {
            <?php 
                $filterBySection = $_GET['srch_section']?? '';
            ?>
            window.location.href = 'export_qanda.php?t=' + t <?= $filterBySection != '' ? " + '&section=' + '" . $filterBySection . "'" : '' ?>;
        } else if (type === 'register') {
            window.location.href = 'export_data.php?t=' + t;
        }
    }
</script>
<style>
    .container {
        text-align: center;
        width: 100%;
        padding-top: 2%;
    }

    .message-field {
        margin-top: 20px;
        font-size: 11px;
    }

    .success {
        color: green;
    }

    .error {
        color: red;
    }
</style>