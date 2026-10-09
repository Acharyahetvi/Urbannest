<?php
session_start();

$action = $_GET['action'] ?? 'continue';

$messages = [
    'wishlist' => [
        'title' => 'Login Required',
        'message' => 'Please login to your UrbanNest account to add properties to your wishlist.'
    ],
    'visit' => [
        'title' => 'Login Required',
        'message' => 'Please login to your UrbanNest account to book a property visit.'
    ],
    'inquiry' => [
        'title' => 'Login Required',
        'message' => 'Please login to your UrbanNest account to send an inquiry.'
    ],
    'review' => [
        'title' => 'Login Required',
        'message' => 'Please login to your UrbanNest account to write a review.'
    ],
    'property' => [
        'title' => 'Login Required',
        'message' => 'Please login to your UrbanNest account to list your property.'
    ],
    'continue' => [
        'title' => 'Login Required',
        'message' => 'Please login to your UrbanNest account to continue.'
    ]
];

$current = $messages[$action] ?? $messages['continue'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($current['title']) ?> | UrbanNest</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            background: #0b1d33;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
            color: #ffffff;
        }

        .restriction-wrapper {
            width: 100%;
            max-width: 560px;
        }

        .restriction-card {
            background: #ffffff;
            color: #222;
            border-radius: 18px;
            padding: 55px 45px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.30);
        }

        .logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 42px;
            font-weight: 700;
            color: #0b1d33;
            margin-bottom: 30px;
        }

        .logo span {
            color: #c9a227;
        }

        .lock-icon {
            width: 85px;
            height: 85px;
            margin: 0 auto 25px;
            border-radius: 50%;
            background: #f7f2e3;
            color: #c9a227;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
        }

        h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 38px;
            color: #0b1d33;
            margin-bottom: 15px;
        }

        .message {
            color: #666;
            font-size: 15px;
            line-height: 1.8;
            max-width: 430px;
            margin: 0 auto 30px;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .btn {
            text-decoration: none;
            padding: 12px 27px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login {
            background: #0b1d33;
            color: #ffffff;
            border: 1px solid #0b1d33;
        }

        .btn-login:hover {
            background: #162d49;
            transform: translateY(-2px);
        }

        .btn-register {
            background: #c9a227;
            color: #ffffff;
            border: 1px solid #c9a227;
        }

        .btn-register:hover {
            background: #b18b1d;
            transform: translateY(-2px);
        }

        .back-home {
            display: inline-block;
            margin-top: 25px;
            color: #777;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.2s ease;
        }

        .back-home:hover {
            color: #0b1d33;
        }

        .back-home i {
            margin-right: 5px;
        }

        @media (max-width: 576px) {
            body {
                padding: 18px;
            }

            .restriction-card {
                padding: 40px 25px;
            }

            .logo {
                font-size: 36px;
            }

            h1 {
                font-size: 32px;
            }

            .buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="restriction-wrapper">

    <div class="restriction-card">

        <div class="logo">
            Urban<span>Nest</span>
        </div>

        <div class="lock-icon">
            <i class="fa-solid fa-lock"></i>
        </div>

        <h1><?= htmlspecialchars($current['title']) ?></h1>

        <p class="message">
            <?= htmlspecialchars($current['message']) ?>
        </p>

        <div class="buttons">

            <a href="login.php" class="btn btn-login">
                <i class="fa-solid fa-right-to-bracket"></i>
                Login
            </a>

            <a href="register.php" class="btn btn-register">
                <i class="fa-solid fa-user-plus"></i>
                Create Account
            </a>

        </div>

        <a href="../index.php" class="back-home">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Home
        </a>

    </div>

</div>

</body>
</html>