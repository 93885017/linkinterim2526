<?php
ini_set('display_errors', 0);
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
date_default_timezone_set('Asia/Hong_Kong');
require_once 'class/dbConnect.php';
require_once 'class/HelperClass.php';
require_once 'class/QandaInfo.php';

$db = new dbConnect();
$conn = $db->connect();
$helperObj = new HelperClass($conn);
$qandaObj = new QandaInfo();

$filter = [];
$filter['conditions']['status'] = '1';

$itemPerPage = 50;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

if (isset($_GET['srch_section']) && $_GET['srch_section'] != '') {
    $filter['conditions']['section'] = $_GET['srch_section'];
}

$filter['pagination'] = [
    'limit' => $itemPerPage,
    'page' => $page,
];
$dataInfo = $qandaObj->getData($conn, false, $filter);

$data = $dataInfo['data'] ?? [];
?>
<html>

<head>
    <meta name="viewport" content="<?= HEADER_VIEWPORT ?>">
    <title>Q&A listing</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="plugin/bootstrap/css/bootstrap.min.css">
    <script src="plugin/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container">
        <?php require_once 'template/admin.commen.php'; ?>
        <h1>Q&A listing</h1>
        <br>
        <div class="table-responsive">
            <p>Total Questions: <?= $dataInfo['total'] ?? 0 ?></p>
            <div style="margin-bottom: 10px;">
                filter by section: <select name="srch_section" onchange="filterBySection(this);">
                    <option value="" <?= (!isset($_GET['srch_section']) || $_GET['srch_section'] == '') ? 'selected' : '' ?>>All</option>
                    <option value="video_page_2" <?= (isset($_GET['srch_section']) && $_GET['srch_section'] == 'video_page_2') ? 'selected' : '' ?>>News Conference</option>
                    <option value="video_page_1" <?= (isset($_GET['srch_section']) && $_GET['srch_section'] == 'video_page_1') ? 'selected' : '' ?>>Analyst</option>
                </select>
            </div>
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>Question</th>
                        <th>Time</th>
                        <th>Date</th>
                        <th>Section</th>
                        <th>IP</th>
                        <th>Device</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($data) > 0) {
                        foreach ($data as $row) {
                            $createdAt = $row['created_at'];
                            $createdAtTime = '';
                            $createdAtDate = '';
                            if ($createdAt) {
                                $createdAtDateTime = new DateTime($createdAt);
                                $createdAtTime = $createdAtDateTime->format("hia");
                                $createdAtDate = $createdAtDateTime->format("d F");
                            }

                            $sectionDisplay = '';
                            switch ($row['section']) {
                                case 'video_page_1':
                                    $sectionDisplay = 'Analyst';
                                    break;
                                case 'video_page_2':
                                    $sectionDisplay = 'News Conference';
                                    break;
                                default:
                                    $sectionDisplay = $row['section'];
                                    break;
                            }

                            echo "<tr>";
                            echo "<td>" . $row['id'] . "<input type='hidden' name='id' value='" . $row['id'] . "'></td>";
                            echo "<td>" . htmlspecialchars((string)$row["last_name"]) . "</td>";
                            echo "<td>" . htmlspecialchars((string)$row['first_name']) . "</td>";
                            echo "<td>" . htmlspecialchars((string)$row["company_name"]) . "</td>";
                            echo "<td>" . htmlspecialchars((string)$row["email"]) . "</td>";
                            echo "<td>" . htmlspecialchars((string)$row["question"]) . "</td>";
                            echo "<td>" . htmlspecialchars((string)$createdAtTime) . "</td>";
                            echo "<td>" . htmlspecialchars((string)$createdAtDate) . "</td>";
                            echo "<td>" . htmlspecialchars((string)$sectionDisplay) . "</td>";
                            echo "<td>" . htmlspecialchars((string)$row["ip"]) . "</td>";
                            echo "<td>" . htmlspecialchars((string)$row["device"]) . "</td>";

                            echo "<td><button class='btn-deactivate' data-id='" . $row['id'] . "'>hide</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No records found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
                <?php
                $totalItems = $dataInfo['total'] ?? 0;
                $totalPages = ceil($totalItems / $itemPerPage);

                if ($totalPages > 1) {
                    echo "<div class='pagination'>";
                
                    // Show the "First" page button
                    if ($page > 1) {
                        echo "<a href='?page=1'>&laquo; First</a>";
                    }
                
                    // Calculate the range of pages to display
                    $start = max(1, $page - 5); // Start 5 pages before the current page
                    $end = min($totalPages, $page + 4); // End 4 pages after the current page
                
                    // Show the range of pages
                    for ($i = $start; $i <= $end; $i++) {
                        echo "<a href='?page=$i' class='" . ($i == $page ? 'active' : '') . "'>$i</a>";
                    }
                
                    // Show the "Last" page button
                    if ($page < $totalPages) {
                        echo "<a href='?page=$totalPages'>Last &raquo;</a>";
                    }
                
                    echo "</div>";
                }
                ?>
        </div>
        <script>
            $(document).ready(function () {
                $('.btn-deactivate').click(function () {
                    var id = $(this).data('id');
                    if (confirm('Are you sure you want to hide this question?')) {
                        $.ajax({
                            url: 'qanda_action.php',
                            type: 'POST',
                            data: {
                                action: 'deactivate',
                                id: id
                            },
                            success: function (response) {
                                if (response.status === 'success') {
                                    alert('Question hidden successfully.');
                                    location.reload();
                                } else {
                                    alert('Error: ' + response.message);
                                }
                            },
                            error: function () {
                                alert('An error occurred while processing the request.');
                            }
                        });
                    }
                });
            });

            function filterBySection(selectObj) {
                var section = selectObj.value;
                var url = new URL(window.location.href);
                if (section) {
                    url.searchParams.set('srch_section', section);
                } else {
                    url.searchParams.delete('srch_section');
                }
                window.location.href = url.toString();
            }
        </script>
    </div>
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            /* Center the pagination */
            padding: 10px 0;
            list-style: none;
        }

        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin: 0 4px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        .pagination a:hover {
            background-color: #007bff;
            /* Hover background color */
            color: white;
            /* Hover text color */
            border-color: #007bff;
        }

        .pagination a.active {
            background-color: #007bff;
            /* Active page background color */
            color: white;
            /* Active page text color */
            border-color: #007bff;
        }
    </style>
</body>

</html>