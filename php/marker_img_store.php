<?php
    $err = "";
    if($_FILES['file']['size']<=15000){

        if(move_uploaded_file($_FILES['file']['tmp_name'], "../images/uploads/marker/".$_FILES['file']['name'])){

            $err = "Image Inserted completely!";
            ?><script type="text/javascript">
                alert("Image is inserted");
                console.log("Image Inserted");
                location.href = "../map.php";
            </script><?php
        }

    } else {

        $err = "The Selected file is Larger than the sugested file size";

    }



?>