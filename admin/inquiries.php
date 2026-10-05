<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* =====================================================
   DATABASE CONNECTION
===================================================== */

require_once __DIR__ . "/db_connection.php";

if (!isset($con) && isset($conn)) {
    $con = $conn;
}

if (!$con) {
    die("Database connection failed.");
}


/* =====================================================
   UPDATE INQUIRY STATUS
===================================================== */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {

    $inquiry_id = (int)($_POST['inquiry_id'] ?? 0);
    $new_status = trim($_POST['status'] ?? '');

    $allowed_statuses = ['Pending', 'Responded', 'Closed'];

    if ($inquiry_id > 0 && in_array($new_status, $allowed_statuses, true)) {

        $updateSql = "UPDATE inquiries
                      SET status = ?
                      WHERE inquiry_id = ?";

        $updateStmt = mysqli_prepare($con, $updateSql);

        if ($updateStmt) {

            mysqli_stmt_bind_param(
                $updateStmt,
                "si",
                $new_status,
                $inquiry_id
            );

            mysqli_stmt_execute($updateStmt);
            mysqli_stmt_close($updateStmt);
        }
    }

    header("Location: inquiries.php");
    exit;
}


/* =====================================================
   GET INQUIRIES
===================================================== */

$sql = "
    SELECT
        i.inquiry_id,
        i.message,
        i.status,
        i.created_at,

        u.full_name,
        u.email,

        p.property_id,
        p.title AS property_title

    FROM inquiries i

    LEFT JOIN users u
        ON i.user_id = u.user_id

    LEFT JOIN properties p
        ON i.property_id = p.property_id

    ORDER BY i.inquiry_id DESC
";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Query Error: " . mysqli_error($con));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Inquiries | UrbanNest Admin</title>

    <!-- BOOTSTRAP -->
    <link
        rel="stylesheet"
        href="/urbannest/admin/assets/css/bootstrap.min.css">

    <!-- COMMON STYLE -->
    <link
        rel="stylesheet"
        href="/urbannest/admin/assets/css/style.css">

    <!-- ADMIN CSS -->
    <link
        rel="stylesheet"
        href="/urbannest/admin/assets/css/admin.css">

    <!-- FONT AWESOME -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


    <style>

        .admin-page {
            padding: 35px;
        }

        .page-header {
            margin-bottom: 25px;
        }

        .page-header .eyebrow {
            color: #d9b52f;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 2px;
        }

        .page-header h1 {
            margin: 5px 0;
            font-size: 32px;
            color: #0b2342;
        }

        .page-header p {
            color: #777;
            margin: 0;
        }

        .table-box {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        .admin-table th {
            background: #0b2342;
            color: #fff;
            padding: 14px;
            text-align: left;
            font-size: 14px;
            white-space: nowrap;
        }

        .admin-table td {
            padding: 14px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
            font-size: 14px;
        }

        .admin-table tr:hover {
            background: #fafafa;
        }

        .user-name {
            font-weight: 600;
            color: #0b2342;
        }

        .user-email {
            color: #777;
            font-size: 13px;
            margin-top: 3px;
        }

        .property-name {
            font-weight: 600;
            color: #0b2342;
        }

        .message-cell {
            max-width: 300px;
            line-height: 1.5;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-responded {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-closed {
            background: #e2e3e5;
            color: #41464b;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #777;
        }

        /* ACTION BUTTONS */

        .inquiry-actions {
            display: flex;
            gap: 7px;
            flex-wrap: wrap;
        }

        .inquiry-actions form {
            margin: 0;
        }

        .btn-action {
            border: none;
            border-radius: 6px;
            padding: 7px 10px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s ease;
            white-space: nowrap;
        }

       .btn-respond {
            background: #198754;
            color: #fff;
        }

        .btn-respond:hover {
            background: #157347;
            color: #fff;
        }

        .btn-close {
            background: #dc3545;
            color: #fff;
        }

        .btn-close:hover {
            background: #bb2d3b;
            color: #fff;
        }

        @media (max-width: 768px) {

            .admin-page {
                padding: 20px;
            }

            .page-header h1 {
                font-size: 26px;
            }

        }

    </style>

</head>


<body>


    <!-- SIDEBAR -->

    <?php
    include __DIR__ . "/includes/sidebar.php";
    ?>


    <!-- MAIN WRAPPER -->

    <div class="un-main-wrapper">


        <!-- HEADER -->

        <?php
        include __DIR__ . "/includes/header.php";
        ?>


        <!-- MAIN CONTENT -->

        <main class="admin-page">


            <!-- PAGE HEADER -->

            <div class="page-header">

                <span class="eyebrow">
                    USER COMMUNICATION
                </span>

                <h1>
                    Inquiries
                </h1>

                <p>
                    View property inquiries submitted by users.
                </p>

            </div>


            <!-- TABLE -->

            <div class="table-box">

                <table class="admin-table">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>User</th>
                            <th>Property</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if (mysqli_num_rows($result) > 0) { ?>

                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                            <tr>


                                <!-- ID -->

                                <td>

                                    #<?php
                                    echo (int)$row['inquiry_id'];
                                    ?>

                                </td>


                                <!-- USER -->

                                <td>

                                    <div class="user-name">

                                        <?php
                                        echo htmlspecialchars(
                                            $row['full_name']
                                            ?? 'Unknown User'
                                        );
                                        ?>

                                    </div>

                                    <div class="user-email">

                                        <?php
                                        echo htmlspecialchars(
                                            $row['email'] ?? ''
                                        );
                                        ?>

                                    </div>

                                </td>


                                <!-- PROPERTY -->

                                <td>

                                    <div class="property-name">

                                        <?php
                                        echo htmlspecialchars(
                                            $row['property_title']
                                            ?? 'Unknown Property'
                                        );
                                        ?>

                                    </div>

                                    <small>

                                        Property ID:

                                        <?php
                                        echo (int)$row['property_id'];
                                        ?>

                                    </small>

                                </td>


                                <!-- MESSAGE -->

                                <td class="message-cell">

                                    <?php
                                    echo nl2br(
                                        htmlspecialchars(
                                            $row['message']
                                        )
                                    );
                                    ?>

                                </td>


                                <!-- STATUS -->

                                <td>

                                    <?php

                                    $status =
                                        strtolower(
                                            trim($row['status'])
                                        );

                                    $statusClass =
                                        'status-pending';

                                    if ($status === 'responded') {

                                        $statusClass =
                                            'status-responded';

                                    }

                                    if ($status === 'closed') {

                                        $statusClass =
                                            'status-closed';

                                    }

                                    ?>

                                    <span
                                        class="status-badge
                                        <?php echo $statusClass; ?>">

                                        <?php
                                        echo htmlspecialchars(
                                            $row['status']
                                        );
                                        ?>

                                    </span>

                                </td>


                                <!-- DATE -->

                                <td>

                                    <?php

                                    echo date(
                                        'd M Y, h:i A',
                                        strtotime(
                                            $row['created_at']
                                        )
                                    );

                                    ?>

                                </td>


                                <!-- ACTION -->

                                <td>

                                    <div class="inquiry-actions">


                                        <?php if ($status !== 'responded') { ?>

                                            <form method="POST">

                                                <input
                                                    type="hidden"
                                                    name="inquiry_id"
                                                    value="<?php echo (int)$row['inquiry_id']; ?>">

                                                <input
                                                    type="hidden"
                                                    name="status"
                                                    value="Responded">

                                                <button
                                                    type="submit"
                                                    name="update_status"
                                                    class="btn-action btn-respond">

                                                    <i class="fa-solid fa-check"></i>
                                                    Responded

                                                </button>

                                            </form>

                                        <?php } ?>


                                        <?php if ($status !== 'closed') { ?>

                                            <form method="POST">

                                                <input
                                                    type="hidden"
                                                    name="inquiry_id"
                                                    value="<?php echo (int)$row['inquiry_id']; ?>">

                                                <input
                                                    type="hidden"
                                                    name="status"
                                                    value="Closed">

                                                <button
                                                    type="submit"
                                                    name="update_status"
                                                    class="btn-action btn-close">

                                                    <i class="fa-solid fa-xmark"></i>
                                                    Close

                                                </button>

                                            </form>

                                        <?php } ?>


                                    </div>

                                </td>


                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>

                            <td
                                colspan="7"
                                class="no-data">

                                <i
                                    class="fa-regular fa-envelope"
                                    style="font-size:30px;">
                                </i>

                                <br><br>

                                No inquiries found.

                            </td>

                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </main>


        <!-- FOOTER -->

        <?php
        include __DIR__ . "/includes/footer.php";
        ?>


    </div>


    <script
        src="/urbannest/admin/assets/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>