<?php  
    header('Content-Type: application/json');

    $allow = ['pdf', 'doc', 'docx', 'txt'];
    $proceed = [];

    

    foreach($_FILES['files']['name'] as $key => $name){
        // if($_FILES['files']['name']['error'][$key]==0){
            $temp = $_FILES['files']['tmp_name'][$key];
            $ext = explode('.', $name);
            $ext = strtolower(end($ext));

            // $file_n = uniqid('', true).time().'.'.$ext;
            $file_n = $name;

            if(in_array($ext, $allow)&&(move_uploaded_file($temp,'../uploads/'.$file_n))){


                // FireStore
                // $cityRef = $db->collection('cities')->document('BJ');
                // $cityRef->set([
                //     'capital' => true
                // ], ['merge' => true]);

                $proceed = array(
                    'name'=> $file_n,
                    'extension'=> $ext,
                    'temp'=> $temp,
                    'upload-status'=> true
                );            
            } else {
                $proceed = array(
                    'name'=> $file_n,
                    'extension'=> $ext,
                    'upload-status'=> false
                );  
            }

            
        // }
    }

    echo json_encode($proceed);
?>