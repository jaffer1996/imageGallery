<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home_style.css">
    <title>Document</title>
</head>
<body>
    <h1 class="mainheading"> {{ Auth::user()->name }}'s Gallery</h1>
    <form method="post" action="logout">
        @csrf
        <input class="logoutbutton"  type="submit" value="LOG OUT" />
    </form>

    <br>
    <br>

    <div class="storagecontainer">
        <h4>Available Storage : {{$available}} MB </h4>
        <?php
            if(Session::has('message')){
        ?>
               <p><?php echo Session::get('message') ?></p> 

        <?php
            }
        ?>
    </div>
    <form class="uploadformcontainer" method="post" action="uploadImage" enctype="multipart/form-data">
        @csrf
        <input type="file" value="Select Image" name="image" />
        <br>
        <input type="submit" value="UPLOAD" />
    </form>

    <div class="wholeimagescontainer">
    <?php
        foreach($images as $image){
    ?>  
                <div class="singleimagecontainer">
                    <img class="images" src="<?php echo 'images/' . $image->name ?>"/>
                    <div>
                    <form method="post" action="delete">
                        @csrf
                        <input class="deletebutton" type="submit" value="Delete" />
                        <input type="hidden" value="<?php echo $image->id ?>" name="id" />
                        <input type="hidden" value="<?php echo $image->name ?>" name="name" />                    </form>
                    </div>
                </div>
    <?php   } ?>
    </div>
    
</body>
</html>