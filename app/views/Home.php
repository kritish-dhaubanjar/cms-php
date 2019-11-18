<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-jscalendar@1.4.4/source/jsCalendar.min.css" />
    <link rel="stylesheet" href="<?php echo URL ?>/css/auth.css">
    <title>CMS Admin</title>
</head>

<body>
    <?php
    if (!isAuth()) {
        ?>
        <div id="login">
            <form action="<?php echo URL ?>/pages/login" method="POST">
                <div id="logo">
                    <img src="<?php echo URL ?>/dist/theGimmickBox.png" width="128px" />
                </div>
                <br>
                <div class="form-group">
                    <input type="email" name="email" class="form-control 
                    <?php if (!empty($data['email_err'])) echo 'is-invalid' ?>" id="email" placeholder="Enter email" value="<?php echo $data['email'] ?>">
                    <?php if (!empty($data["email_err"])) { ?>
                        <div class="invalid-feedback">
                            <?php echo $data["email_err"] ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control <?php if (!empty($data['password_err'])) echo 'is-invalid' ?>" id="password" placeholder="Password">
                    <?php if (!empty($data["password_err"])) { ?>
                        <div class="invalid-feedback">
                            <?php echo $data["password_err"] ?>
                        </div>
                    <?php } ?>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
        </div>
    <?php
    } else {
        ?>
        <div id="app"></div>
        <script src="<?php echo URL ?>/js/build.js"></script>
    <?php
    }
    ?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/f3706e4ce7.js"></script>
</body>

</html>
