<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.75);
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="wrapper mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Delete Book</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="book_id" value="<?php echo trim($_GET["book_id"]); ?>"/>
                            <p>Hey there! Are you sure you want to delete this book record?</p>
                            <p>
                                <input type="submit" value="Yep, delete it" class="btn btn-danger">
                                <a href="adminbooks.php" class="btn btn-secondary">Nope, keep it</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
