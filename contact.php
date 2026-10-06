
<?php
$pageTitle = 'Contact — UrbanNest';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<style>
    /* Contact Page */

    .contact-hero {
        background: #0b1f3a;
        color: white;
        padding: 60px 0;
    }

    .contact-hero h1 {
        color: #fff;
        font-size: 38px;
        font-weight: 700;
    }

    .contact-section {
        padding: 60px 0;
        background: #f8f7f2;
    }

    .contact-info {
        background: #f8f9fa;
        padding: 30px;
        border-radius: 12px;
        height: 100%;
    }

    .contact-info h3 {
        color: #0b1f3a;
        font-weight: 700;
    }

    .contact-info p {
        color: #666;
        line-height: 1.7;
    }

    .contact-form {
        background: white;
        padding: 30px;
        border: 1px solid #eee;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .contact-form h3 {
        color: #0b1f3a;
        font-weight: 700;
    }

    .form-label {
        font-weight: 500;
    }

    .form-control,
    .form-select {
        padding: 12px;
        border-radius: 6px;
    }

    .btn-contact {
        background: #d4af37;
        color: #0b1f3a;
        padding: 12px 25px;
        font-weight: 600;
        border: none;
        border-radius: 6px;
    }

    .btn-contact:hover {
        background: #c19b24;
        color: #0b1f3a;
    }
</style>


<!-- Hero Section -->

<section class="contact-hero">
    <div class="container">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?= url('index.php') ?>" class="text-white">
                        Home
                    </a>
                </li>

                <li class="breadcrumb-item active text-white">
                    Contact
                </li>
            </ol>
        </nav>

        <h1>Let's Find Your Next Home</h1>

        <p class="mb-0">
            Have questions about a property?
            Contact UrbanNest and tell us what you're looking for.
        </p>

    </div>
</section>


<!-- Contact Section -->

<section class="contact-section">

    <div class="container">

        <div class="row g-4">

            <!-- Contact Information -->

            <div class="col-lg-5">

                <div class="contact-info">

                    <h3>Get in Touch</h3>

                    <p>
                        Looking to buy, sell, or rent a property?
                        Fill out the contact form and share your requirements.
                    </p>

                    <hr>

                    <h5>Our Services</h5>

                    <p>
                        <strong>Buying a Property</strong><br>
                        Find a property that matches your needs.
                    </p>

                    <p>
                        <strong>Selling a Property</strong><br>
                        Get information about listing your property.
                    </p>

                    <p>
                        <strong>Renting a Property</strong><br>
                        Explore properties available for rent.
                    </p>

                    <p>
                        <strong>Property Investment</strong><br>
                        Share your property investment requirements.
                    </p>

                    <hr>

                    <p class="mb-0">
                        Please fill in the form with your details
                        so we can understand your requirements.
                    </p>

                </div>

            </div>


            <!-- Contact Form -->

            <div class="col-lg-7">

                <div class="contact-form">

                    <h3 class="mb-4">Send Us a Message</h3>

                    <form id="contactForm">

                        <div class="row g-3">

                            <!-- First Name -->

                            <div class="col-md-6">

                                <label class="form-label">
                                    First Name
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="firstName"
                                    placeholder="Enter first name"
                                    required>

                            </div>


                            <!-- Last Name -->

                            <div class="col-md-6">

                                <label class="form-label">
                                    Last Name
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="lastName"
                                    placeholder="Enter last name"
                                    required>

                            </div>


                            <!-- Email -->

                            <div class="col-md-6">

                                <label class="form-label">
                                    Email Address
                                </label>

                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    placeholder="Enter email"
                                    required>

                            </div>


                            <!-- Phone -->

                            <div class="col-md-6">

                                <label class="form-label">
                                    Phone Number
                                </label>

                                <input
                                    type="tel"
                                    class="form-control"
                                    id="phone"
                                    placeholder="Enter phone number">

                            </div>


                            <!-- Interest -->

                            <div class="col-md-6">

                                <label class="form-label">
                                    Interested In
                                </label>

                                <select
                                    class="form-select"
                                    id="interest"
                                    required>

                                    <option value="">
                                        Select Interest
                                    </option>

                                    <option value="Buying">
                                        Buying
                                    </option>

                                    <option value="Selling">
                                        Selling
                                    </option>

                                    <option value="Renting">
                                        Renting
                                    </option>

                                    <option value="Investment">
                                        Investment
                                    </option>

                                    <option value="Other">
                                        Other
                                    </option>

                                </select>

                            </div>


                            <!-- Budget -->

                            <div class="col-md-6">

                                <label class="form-label">
                                    Budget
                                </label>

                                <select
                                    class="form-select"
                                    id="budget">

                                    <option value="">
                                        Select Budget
                                    </option>

                                    <option value="Under 25 Lakh">
                                        Under ₹25 Lakh
                                    </option>

                                    <option value="25-50 Lakh">
                                        ₹25 Lakh – ₹50 Lakh
                                    </option>

                                    <option value="50 Lakh-1 Crore">
                                        ₹50 Lakh – ₹1 Crore
                                    </option>

                                    <option value="1-2 Crore">
                                        ₹1 Crore – ₹2 Crore
                                    </option>

                                    <option value="Above 2 Crore">
                                        Above ₹2 Crore
                                    </option>

                                </select>

                            </div>


                            <!-- Message -->

                            <div class="col-12">

                                <label class="form-label">
                                    Message
                                </label>

                                <textarea
                                    class="form-control"
                                    id="message"
                                    rows="5"
                                    placeholder="Tell us about your requirements..."
                                    required></textarea>

                            </div>


                            <!-- Checkbox -->

                            <div class="col-12">

                                <div class="form-check">

                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        id="agree"
                                        required>

                                    <label
                                        class="form-check-label"
                                        for="agree">
                                        I agree to be contacted regarding
                                        my property inquiry.
                                    </label>

                                </div>

                            </div>


                            <!-- Submit Button -->

                            <div class="col-12">

                                <button
                                    type="submit"
                                    class="btn-contact">
                                    Send Message
                                    <i class="fa-solid fa-paper-plane ms-2"></i>
                                </button>

                            </div>

                        </div>

                    </form>

                    <!-- Success Message -->

                    <div
                        id="successMessage"
                        class="alert alert-success mt-4 d-none">
                        Thank you! Your form has been submitted successfully.
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- JavaScript -->

<script>
    document.getElementById("contactForm").addEventListener("submit", function(event) {

        event.preventDefault();

        const firstName = document.getElementById("firstName").value.trim();
        const lastName = document.getElementById("lastName").value.trim();
        const email = document.getElementById("email").value.trim();
        const interest = document.getElementById("interest").value;
        const message = document.getElementById("message").value.trim();

        if (
            firstName === "" ||
            lastName === "" ||
            email === "" ||
            interest === "" ||
            message === ""
        ) {
            alert("Please fill in all required fields.");
            return;
        }

        document.getElementById("successMessage").classList.remove("d-none");

        this.reset();

    });
</script>


<?php require_once __DIR__ . '/includes/footer.php'; ?>
