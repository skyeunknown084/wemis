<?php
// Database details
$db_server   = 'localhost';
$db_username = 'root';
$db_password = '1234';
$db_name     = 'dbcrudtest';

// Get members (and id)
$members = '';
$id  = '';
if (isset($_GET['members'])){
  $members = $_GET['members'];
  if ($members == 'get_members' ||
      $members == 'get_member'   ||
      $members == 'add_member'   ||
      $members == 'edit_member'  ||
      $members == 'delete_member'){
    if (isset($_GET['id'])){
      $id = $_GET['id'];
      if (!is_numeric($id)){
        $id = '';
      }
    }
  } else {
    $members = '';
  }
}

// Prepare array
$mysql_data = array();

// Valid members found
if ($members != ''){
  
  // Connect to database
  $db_connection = mysqli_connect($db_server, $db_username, $db_password, $db_name);
  if (mysqli_connect_errno()){
    $result  = 'error';
    $message = 'Failed to connect to database: ' . mysqli_connect_error();
    $members     = '';
  }
  
  // Execute members
  if ($members == 'get_members'){
    
    // Get companies
    $query = "SELECT * FROM it_companies ORDER BY id";
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
      while ($company = mysqli_fetch_array($query)){
        $functions  = '<div class="function_buttons"><ul>';
        $functions .= '<li class="function_edit"><a data-id="'   . $company['company_id'] . '" data-name="' . $company['company_name'] . '"><span>Edit</span></a></li>';
        $functions .= '<li class="function_delete"><a data-id="' . $company['company_id'] . '" data-name="' . $company['company_name'] . '"><span>Delete</span></a></li>';
        $functions .= '</ul></div>';
        $mysql_data[] = array(
          "ID"          => $company['rank'],
          "company_name"  => $company['company_name'],
          "industries"    => $company['industries'],
          "revenue"       => '$ ' . $company['revenue'],
          "fiscal_year"   => $company['fiscal_year'],
          "employees"     => number_format($company['employees'], 0, '.', ','),
          "market_cap"    => '$ ' . $company['market_cap'],
          "headquarters"  => $company['headquarters'],
          "functions"     => $functions
        );
      }
    }
    
  } elseif ($members == 'get_company'){
    
    // Get company
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "SELECT * FROM it_companies WHERE company_id = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
        while ($company = mysqli_fetch_array($query)){
          $mysql_data[] = array(
            "rank"          => $company['rank'],
            "company_name"  => $company['company_name'],
            "industries"    => $company['industries'],
            "revenue"       => $company['revenue'],
            "fiscal_year"   => $company['fiscal_year'],
            "employees"     => $company['employees'],
            "market_cap"    => $company['market_cap'],
            "headquarters"  => $company['headquarters']
          );
        }
      }
    }
  
  } elseif ($members == 'add_company'){
    
    // Add company
    $query = "INSERT INTO it_companies SET ";
    if (isset($_GET['rank']))         { $query .= "rank         = '" . mysqli_real_escape_string($db_connection, $_GET['rank'])         . "', "; }
    if (isset($_GET['company_name'])) { $query .= "company_name = '" . mysqli_real_escape_string($db_connection, $_GET['company_name']) . "', "; }
    if (isset($_GET['industries']))   { $query .= "industries   = '" . mysqli_real_escape_string($db_connection, $_GET['industries'])   . "', "; }
    if (isset($_GET['revenue']))      { $query .= "revenue      = '" . mysqli_real_escape_string($db_connection, $_GET['revenue'])      . "', "; }
    if (isset($_GET['fiscal_year']))  { $query .= "fiscal_year  = '" . mysqli_real_escape_string($db_connection, $_GET['fiscal_year'])  . "', "; }
    if (isset($_GET['employees']))    { $query .= "employees    = '" . mysqli_real_escape_string($db_connection, $_GET['employees'])    . "', "; }
    if (isset($_GET['market_cap']))   { $query .= "market_cap   = '" . mysqli_real_escape_string($db_connection, $_GET['market_cap'])   . "', "; }
    if (isset($_GET['headquarters'])) { $query .= "headquarters = '" . mysqli_real_escape_string($db_connection, $_GET['headquarters']) . "'";   }
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
    }
  
  } elseif ($members == 'edit_company'){
    
    // Edit company
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "UPDATE it_companies SET ";
      if (isset($_GET['rank']))         { $query .= "rank         = '" . mysqli_real_escape_string($db_connection, $_GET['rank'])         . "', "; }
      if (isset($_GET['company_name'])) { $query .= "company_name = '" . mysqli_real_escape_string($db_connection, $_GET['company_name']) . "', "; }
      if (isset($_GET['industries']))   { $query .= "industries   = '" . mysqli_real_escape_string($db_connection, $_GET['industries'])   . "', "; }
      if (isset($_GET['revenue']))      { $query .= "revenue      = '" . mysqli_real_escape_string($db_connection, $_GET['revenue'])      . "', "; }
      if (isset($_GET['fiscal_year']))  { $query .= "fiscal_year  = '" . mysqli_real_escape_string($db_connection, $_GET['fiscal_year'])  . "', "; }
      if (isset($_GET['employees']))    { $query .= "employees    = '" . mysqli_real_escape_string($db_connection, $_GET['employees'])    . "', "; }
      if (isset($_GET['market_cap']))   { $query .= "market_cap   = '" . mysqli_real_escape_string($db_connection, $_GET['market_cap'])   . "', "; }
      if (isset($_GET['headquarters'])) { $query .= "headquarters = '" . mysqli_real_escape_string($db_connection, $_GET['headquarters']) . "'";   }
      $query .= "WHERE company_id = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query  = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
      }
    }
    
  } elseif ($members == 'delete_company'){
  
    // Delete company
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "DELETE FROM it_companies WHERE company_id = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
      }
    }
  
  }
  
  // Close database connection
  mysqli_close($db_connection);

}

// Prepare data
$data = array(
  "result"  => $result,
  "message" => $message,
  "data"    => $mysql_data
);

// Convert PHP array to JSON array
$json_data = json_encode($data);
print $json_data;
?>