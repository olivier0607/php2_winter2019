<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>Signin Template · Bootstrap</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.3/examples/sign-in/">

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap2.min.css" rel="stylesheet" crossorigin="anonymous">


    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
</head>
<body class="text-center">
<div class="container theme-showcase" role="main">
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <!--<div class="jumbotron">-->


    <h2><?php echo 'Hello ' . $_SESSION['REMOTE_USER'] . '. You are already logged in.' ?></h2>

    <form class="form-signin" action="../../../users" method="post">
        <button class="btn btn-lg btn-primary btn-block" name="logout" type="submit" value="1">Logout</button>
        <p class="h3 mb-3 font-weight-normal"><a href="../../index">Home</a></p>
    </form>
    <!--</div> /jumbotron -->
</div> <!-- /container -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="js/ie10-viewport-bug-workaround.js"></script>


</body>
</html>