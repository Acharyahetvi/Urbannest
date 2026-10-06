<?php

session_start();

$pageTitle = 'Book a Visit — UrbanNest';
$pageDesc = 'Book a property visit with UrbanNest.';

require_once __DIR__ . '/db_connection.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';


// =====================================
// GET PROPERTY ID
// =====================================

$property_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;


// =====================================
// CHECK USER LOGIN
// =====================================

if (!isset($_SESSION['user_id'])) {

?>

    <section class="appointment-section">

        <div class="container">

            <div class="appointment-message">

                <i class="fa-solid fa-user-lock"></i>

                <h2>Please Login First</h2>

                <p>
                    Please login to your UrbanNest account before booking a property visit.
                </p>

                <a href="auth/login.php" class="appointment-btn">
                    Login
                </a>

                <a href="auth/register.php" class="register-btn">
                    Create Account
                </a>

            </div>

        </div>

    </section>

<?php

    require_once __DIR__ . '/includes/footer.php';

    exit;
}


// =====================================
// GET USER ID
// =====================================

$user_id = $_SESSION['user_id'];


// =====================================
// GET PROPERTY DETAILS
// =====================================

$property = null;

if ($property_id > 0) {

    $sql = "SELECT *
            FROM properties
            WHERE property_id = $property_id
            AND approval_status = 'Approved'
            AND status = 'Active'";

    $result = mysqli_query($conn, $sql);

    if ($result) {

        $property = mysqli_fetch_assoc($result);
    }
}


// =====================================
// APPOINTMENT MESSAGE
// =====================================

$message = "";
$message_type = "";


// =====================================
// BOOK APPOINTMENT
// =====================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $visit_date = $_POST['visit_date'] ?? '';
    $visit_time = $_POST['visit_time'] ?? '';

    if (!is_string($visit_date) || !is_string($visit_time) || empty($visit_date) || empty($visit_time)) {

        $message = "Please select visit date and time.";
        $message_type = "error";
    } else {

        $sql = "INSERT INTO appoinments
                (user_id, property_id, visit_date, visit_time, status)
                VALUES (?, ?, ?, ?, 'Pending')";

        $stmt = mysqli_prepare($conn, $sql);
        $result = false;

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "iiss", $user_id, $property_id, $visit_date, $visit_time);
            $result = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        if ($result) {

            $message = "Your property visit has been booked successfully.";
            $message_type = "success";
        } else {

            $message = "Something went wrong. Please try again.";
            $message_type = "error";
        }
    }
}

?>


<style>
    /* =====================================
   APPOINTMENT PAGE
===================================== */

    .appointment-section {
        padding: 70px 0;
        background: #f8f7f2;
        min-height: 600px;
    }


    /* =====================================
   APPOINTMENT CARD
===================================== */

    .appointment-card {
        max-width: 650px;
        margin: auto;
        background: #ffffff;
        padding: 35px;
        border-radius: 12px;
        border: 1px solid #eee9dc;
        box-shadow: 0 5px 18px rgba(11, 31, 58, 0.08);
    }


    /* =====================================
   HEADING
===================================== */

    .appointment-card h1 {
        color: #0b1f3a;
        font-size: 30px;
        font-weight: 700;
        margin-bottom: 10px;
        text-align: center;
    }

    .appointment-card .subtitle {
        color: #687386;
        text-align: center;
        margin-bottom: 30px;
    }


    /* =====================================
   PROPERTY NAME
===================================== */

    .property-name {
        background: #f8f7f2;
        padding: 18px;
        border-radius: 8px;
        margin-bottom: 25px;
    }

    .property-name strong {
        display: block;
        color: #0b1f3a;
        font-size: 15px;
        margin-bottom: 5px;
    }

    .property-name span {
        color: #687386;
        font-size: 15px;
    }


    /* =====================================
   FORM
===================================== */

    .appointment-form label {
        display: block;
        color: #0b1f3a;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .appointment-form input,
    .appointment-form select {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #ddd;
        border-radius: 7px;
        margin-bottom: 20px;
        font-size: 15px;
    }

    .appointment-form input:focus,
    .appointment-form select:focus {
        border-color: #d4af37;
        outline: none;
    }


    /* =====================================
   BOOK BUTTON
===================================== */

    .book-btn {
        width: 100%;
        border: none;
        padding: 13px;
        background: #0b1f3a;
        color: #fff;
        border-radius: 7px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
    }

    .book-btn:hover {
        background: #d4af37;
        color: #0b1f3a;
    }


    /* =====================================
   MESSAGE
===================================== */

    .success-message {
        background: #e9f7ef;
        color: #26734d;
        padding: 12px 15px;
        border-radius: 7px;
        margin-bottom: 20px;
        text-align: center;
    }

    .error-message {
        background: #fdecec;
        color: #a33a3a;
        padding: 12px 15px;
        border-radius: 7px;
        margin-bottom: 20px;
        text-align: center;
    }


    /* =====================================
   LOGIN MESSAGE
===================================== */

    .appointment-message {
        max-width: 600px;
        margin: 50px auto;
        background: #fff;
        padding: 45px 30px;
        text-align: center;
        border-radius: 12px;
        box-shadow: 0 5px 18px rgba(11, 31, 58, 0.08);
    }

    .appointment-message i {
        font-size: 45px;
        color: #d4af37;
        margin-bottom: 20px;
    }

    .appointment-message h2 {
        color: #0b1f3a;
        margin-bottom: 10px;
    }

    .appointment-message p {
        color: #687386;
        margin-bottom: 25px;
    }

    .appointment-btn,
    .register-btn {
        display: inline-block;
        padding: 11px 22px;
        text-decoration: none;
        border-radius: 7px;
        font-weight: 600;
        margin: 5px;
    }

    .appointment-btn {
        background: #0b1f3a;
        color: #fff;
    }

    .appointment-btn:hover {
        background: #d4af37;
        color: #0b1f3a;
    }

    .register-btn {
        background: #d4af37;
        color: #0b1f3a;
    }

    .register-btn:hover {
        background: #0b1f3a;
        color: #fff;
    }


    /* =====================================
   PROPERTY NOT FOUND
===================================== */

    .property-not-found {
        max-width: 600px;
        margin: 50px auto;
        background: #fff;
        padding: 40px;
        text-align: center;
        border-radius: 12px;
    }

    .property-not-found h2 {
        color: #0b1f3a;
    }

    .property-not-found p {
        color: #687386;
    }
</style>


<!-- =====================================
     APPOINTMENT SECTION
===================================== -->

<section class="appointment-section">

    <div class="container">


        <?php if (!$property) { ?>


            <!-- PROPERTY NOT FOUND -->

            <div class="property-not-found">

                <h2>Property Not Found</h2>

                <p>
                    Sorry, this property is not available for booking.
                </p>

                <a href="buy.php" class="appointment-btn">
                    Browse Properties
                </a>

            </div>


        <?php } else { ?>


            <!-- APPOINTMENT CARD -->

            <div class="appointment-card">

                <h1>Book a Visit</h1>

                <p class="subtitle">
                    Schedule a visit to this property
                </p>


                <!-- PROPERTY NAME -->

                <div class="property-name">

                    <strong>Property</strong>

                    <span>
                        <?php echo htmlspecialchars($property['title']); ?>
                    </span>

                </div>


                <!-- MESSAGE -->

                <?php if (!empty($message)) { ?>

                    <div class="<?php echo ($message_type == 'success') ? 'success-message' : 'error-message'; ?>">

                        <?php echo htmlspecialchars($message); ?>

                    </div>

                <?php } ?>


                <?php if ($message_type != "success") { ?>


                    <!-- APPOINTMENT FORM -->

                    <form
                        method="POST"
                        action="appointment.php?id=<?php echo $property_id; ?>"
                        class="appointment-form">


                        <!-- VISIT DATE -->

                        <label>
                            Visit Date
                        </label>

                        <input
                            type="date"
                            name="visit_date"
                            required>


                        <!-- VISIT TIME -->

                        <label>
                            Visit Time
                        </label>

                        <select name="visit_time" required>

                            <option value="">
                                Select Visit Time
                            </option>

                            <option value="10:00:00">
                                10:00 AM
                            </option>

                            <option value="11:00:00">
                                11:00 AM
                            </option>

                            <option value="12:00:00">
                                12:00 PM
                            </option>

                            <option value="14:00:00">
                                2:00 PM
                            </option>

                            <option value="15:00:00">
                                3:00 PM
                            </option>

                            <option value="16:00:00">
                                4:00 PM
                            </option>

                            <option value="17:00:00">
                                5:00 PM
                            </option>

                        </select>


                        <!-- SUBMIT -->

                        <button
                            type="submit"
                            class="book-btn">

                            <i class="fa-solid fa-calendar-check"></i>

                            Book Appointment

                        </button>

                    </form>


                <?php } else { ?>


                    <!-- SUCCESS -->

                    <div style="text-align:center;">

                        <a
                            href="properties/details.php?id=<?php echo $property_id; ?>"
                            class="appointment-btn">

                            Back to Property

                        </a>

                        <a
                            href="index.php"
                            class="register-btn">

                            Go to Home

                        </a>

                    </div>


                <?php } ?>

            </div>


        <?php } ?>

    </div>

</section>


<?php

require_once __DIR__ . '/includes/footer.php';

?>