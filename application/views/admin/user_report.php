<table border="1">
    <tr>

        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        
        <th>City</th>
        <th>Last Login</th>
        <th>Connect to Sherpa</th>
        <th>Registration Date</th>
        <th>Total Search</th>
        <th>Total Tribe</th>
        <th>Moving Date</th>
        <th>Min Price</th>
        <th>Max Price</th>
        <th>Test User</th>
        <th>Sherpa Signed up</th>
       

    </tr>
    <?php
    //print_r($newuser);exit();
    foreach ($newuser as $user) {

        // $date = $user['createdAt'];
        // $registrationdate = date("jS M Y", strtotime($date));
        // $logindate = $user['lastLogin'];
        //  $lastlogindate = date("jS M Y", strtotime($logindate));
		$logindate = $user['lastLogin'];
        if($logindate){
            $logindate = date('d M Y', strtotime($user['lastLogin']));
        }else{
            $logindate = '';
        }

        $createdAt = $user['createdAt'];
        if($createdAt){
            $createdAt = date('d M Y', strtotime($user['createdAt']));
        }else{
            $createdAt = '';
        }

        $idealMovingDate = $user['idealMovingDate'];
        if($idealMovingDate){
            $idealMovingDate = date('d M Y', strtotime($user['idealMovingDate']));
        }else{
            $idealMovingDate = '';
        }

        $sherpaSignedup = $user['sherpaSignedup'];
        if($sherpaSignedup){
            $sherpaSignedup = date('d M Y', strtotime($user['sherpaSignedup']));
        }else{
            $sherpaSignedup = '';
        }
		
       $sherpa = $user['connectSherpaFlag'];
       if($sherpa== 0){
           $flag = 'No';
       }else{
           $flag = 'Yes'; 
       }
        echo '
    <tr>
        <td>' . $user['firstName'] . '</td>
        <td>' . $user['lastName'] . '</td>
        <td>' . $user['email'] . '</td>
        
        <td>' . $user['city'] . '</td>
        <td>' . $logindate . '</td>
        <td>' . $flag . '</td>
        
        <td>' . $createdAt . '</td>
        <td>' . $user['no_search'] . '</td>
        <td>' . $user['no_tribe']. '</td>
        <td>' . $idealMovingDate. '</td>
        <td>' . $user['minPrice']. '</td>
        <td>' . $user['maxPrice'] . '</td>
        <td>' . $user['testFlag'] . '</td>
        <td>' . $sherpaSignedup . '</td>
        
    </tr>
    ';
    }
    ?>