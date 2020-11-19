<?php
    if(isset($_POST['submit'])){

        $fileCount = count($_FILES['file']['name']);
        for($i=0; $i < $fileCount; $i++){
            $fileName = $_FILES['file']['name'][$i];
            move_uploaded_file($_FILES['file']['tmp_name'][$i], 'uploads/'.$fileName);
            echo "'$fileName'";
        }
    }
?>

<body>
    <form action='' method='post' enctype="multipart/form-data">
        <input type='file' name='file[]' id='file' multiple>

        <input type='submit' name='submit' value='upload'>
    </form>
</body>