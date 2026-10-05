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
   UPDATE APPOINTMENT STATUS
===================================================== */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {

    $appointment_id = (int)($_POST['appoinment_id'] ?? 0);
    $new_status = trim($_POST['status'] ?? '');

    $allowed_statuses = ['Pending', 'Approved', 'Rejected'];

    if ($appointment_id > 0 && in_array($new_status, $allowed_statuses, true)) {

        $updateSql = "UPDATE appoinments
                      SET status = ?
                      WHERE appoinment_id = ?";

        $updateStmt = mysqli_prepare($con, $updateSql);

        if ($updateStmt) {
            mysqli_stmt_bind_param(
                $updateStmt,
                "si",
                $new_status,
                $appointment_id
            );

            mysqli_stmt_execute($updateStmt);
            mysqli_stmt_close($updateStmt);
        }
    }

    header("Location: appointments.php");
    exit;
}

/* =====================================================
   GET APPOINTMENTS
===================================================== */

$sql = "
    SELECT
        a.appoinment_id,
        a.visit_date,
        a.visit_time,
        a.status,

        u.full_name,
        u.email,

        p.property_id,
        p.title AS property_title

    FROM appoinments a

    LEFT JOIN users u
        ON a.user_id = u.user_id

    LEFT JOIN properties p
        ON a.property_id = p.property_id

    ORDER BY a.appoinment_id DESC
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

    <title>Appointments | UrbanNest Admin</title>

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
            min-width: 800px;
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
            vertical-align: middle;
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

        .status-approved {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-rejected {
            background: #f8d7da;
            color: #842029;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #777;
        }

        @media (max-width: 768px) {

            .admin-page {
                padding: 20px;
            }

            .page-header h1 {
                font-size: 26px;
            }

        }

        .appointment-actions {
            display: flex;
            gap: 7px;
            flex-wrap: wrap;
        }

        .appointment-actions form {
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
        }

        .btn-approve {
            background: #d1e7dd;
            color: #0f5132;
        }

        .btn-approve:hover {
            background: #b7dbc8;
        }

        .btn-reject {
            background: #f8d7da;
            color: #842029;
        }

        .btn-reject:hover {
            background: #f1bfc4;
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
                    VISIT MANAGEMENT
                </span>

                <h1>
                    Appointments
                </h1>

                <p>
                    View property visit appointments booked by users.
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
                            <th>Visit Date</th>
                            <th>Visit Time</th>
                            <th>Status</th>
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
                                    echo (int)$row['appoinment_id'];
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


                                <!-- DATE -->

                                <td>

                                    <?php
                                    echo date(
                                        'd M Y',
                                        strtotime(
                                            $row['visit_date']
                                        )
                                    );
                                    ?>

                                </td>


                                <!-- TIME -->

                                <td>

                                    <?php
                                    echo date(
                                        'h:i A',
                                        strtotime(
                                            $row['visit_time']
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

                                    if ($status === 'approved') {
                                        $statusClass =
                                            'status-approved';
                                    }

                                    if ($status === 'rejected') {
                                        $statusClass =
                                            'status-rejected';
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

                                                                <!-- ACTION -->

                                <td>

                                    <div class="appointment-actions">

                                        <?php if ($status !== 'approved') { ?>

                                            <form method="POST">

                                                <input
                                                    type="hidden"
                                                    name="appoinment_id"
                                                    value="<?php echo (int)$row['appoinment_id']; ?>">

                                                <input
                                                    type="hidden"
                                                    name="status"
                                                    value="Approved">

                                                <button
                                                    type="submit"
                                                    name="update_status"
                                                    class="btn-action btn-approve">

                                                    <i class="fa-solid fa-check"></i>
                                                    Approve

                                                </button>

                                            </form>

                                        <?php } ?>


                                        <?php if ($status !== 'rejected') { ?>

                                            <form method="POST">

                                                <input
                                                    type="hidden"
                                                    name="appoinment_id"
                                                    value="<?php echo (int)$row['appoinment_id']; ?>">

                                                <input
                                                    type="hidden"
                                                    name="status"
                                                    value="Rejected">

                                                <button
                                                    type="submit"
                                                    name="update_status"
                                                    class="btn-action btn-reject">

                                                    <i class="fa-solid fa-xmark"></i>
                                                    Reject

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
                                colspan="6"
                                class="no-data">

                                <i
                                    class="fa-regular fa-calendar-xmark"
                                    style="font-size:30px;">
                                </i>

                                <br><br>

                                No appointments found.

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