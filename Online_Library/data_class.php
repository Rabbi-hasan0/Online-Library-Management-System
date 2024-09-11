<?php include("db.php");

class data extends db {

    private $bookpic;
    private $bookname;
    private $bookdetail;
    private $bookaudor;
    private $bookpub;
    private $branch;
    private $bookprice;
    private $bookquantity;
    private $type;

    private $book;
    private $userselect;
    private $days;
    private $getdate;
    private $returnDate;





    function __construct() {
        // echo " constructor ";
        echo "</br></br>";
    }
    
    public function userLogin($email, $password) {
        // Prepare SQL statement to avoid SQL injection
        $stmt = $this->connection->prepare("SELECT * FROM userdata WHERE email = ?");
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
    
        // Execute the query
        $stmt->execute();
    
        // Fetch the result
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Verify the password using password_verify
            if (password_verify($password, $row['pass'])) {
                // If password is correct, redirect to the user dashboard
                $logid = $row['id'];
                header("Location: otheruser_dashboard.php?userlogid=$logid");
                exit(); // Stop further script execution after redirection
            } else {
                // Incorrect password
                header("Location: index.php?msg=Invalid Credentials");
                exit();
            }
        } else {
            // No user found with the provided email
            header("Location: index.php?msg=Invalid Credentials");
            exit();
        }
    }
    

    public function adminLogin($email, $password) {
        // Prepare the SQL statement to prevent SQL injection
        $stmt = $this->connection->prepare("SELECT id, pass FROM admin WHERE email = ?");
        
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();  // Store the result for checking
    
            // Check if the email exists
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $hashed_password);
                $stmt->fetch();
    
                // Verify the password
                if (password_verify($password, $hashed_password)) {
                    // Start session and set session variables
                    session_start();
                    $_SESSION['admin_id'] = $id;
                    $_SESSION['admin_email'] = $email;
                    
                    // Redirect to the dashboard
                    header("Location: admin_service_dashboard.php");
                    exit();  // Ensure the script stops after redirection
                } else {
                    // Invalid password
                    header("Location: index.php?msg=Invalid Credentials");
                    exit();  // Ensure the script stops after redirection
                }
            } else {
                // Email not found
                header("Location: index.php?msg=Invalid Credentials");
                exit();  // Ensure the script stops after redirection
            }
            
            $stmt->close();  // Close the prepared statement
        } else {
            // Error preparing the statement
            header("Location: index.php?msg=Error with login");
            exit();  // Ensure the script stops after redirection
        }
    }

    public function addbook($bookpic, $bookname, $bookdetail, $bookaudor, $bookpub, $branch, $bookprice, $bookquantity) {
        // Prepare SQL query
        $sql = "INSERT INTO book (bookpic, bookname, bookdetail, bookaudor, bookpub, branch, bookprice, bookquantity, bookava, bookrent) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
        // Prepare statement
        $stmt = $this->connection->prepare($sql);
        
        // Check if prepare was successful
        if ($stmt === false) {
            die("Prepare failed: " . $this->connection->error);
        }
    
        // Bind parameters
        $bookava = $bookquantity; // Assuming the number of available books initially equals the quantity added
        $bookrent = 0; // Initial rent count is 0
    
        $stmt->bind_param("ssssssdii", $bookpic, $bookname, $bookdetail, $bookaudor, $bookpub, $branch, $bookprice, $bookquantity, $bookava, $bookrent);
    
        // Execute statement
        if ($stmt->execute()) {
            header("Location: admin_service_dashboard.php?msg=done");
        } else {
            header("Location: admin_service_dashboard.php?msg=fail");
        }
    
        // Close statement
        $stmt->close();
    }
    


    private $id;



    function getissuebook($userloginid) {

        $newfine="";
        $issuereturn="";

        $q="SELECT * FROM issuebook where userid='$userloginid'";
        $recordSetss=$this->connection->query($q);


        foreach($recordSetss->fetchAll() as $row) {
            $issuereturn=$row['issuereturn'];
            $fine=$row['fine'];
            $newfine= $fine;

            
                //  $newbookrent=$row['bookrent']+1;
        }


        $getdate= date("d/m/Y");
        if($issuereturn<$getdate){
            $q="UPDATE issuebook SET fine='$newfine' where userid='$userloginid'";

            if($this->connection->exec($q)) {
                $q="SELECT * FROM issuebook where userid='$userloginid' ";
                $data=$this->connection->query($q);
                return $data;
            }
            else{
                $q="SELECT * FROM issuebook where userid='$userloginid' ";
                $data=$this->connection->query($q);
                return $data;  
            }

        }
        else{
            $q="SELECT * FROM issuebook where userid='$userloginid'";
            $data=$this->connection->query($q);
            return $data;

        }






    }

    function getbook() {
        $q="SELECT * FROM book ";
        $data=$this->connection->query($q);
        return $data;
    }
    function getbookissue(){
        $q="SELECT * FROM book where bookava !=0 ";
        $data=$this->connection->query($q);
        return $data;
    }

    function userdata() {
        $q="SELECT * FROM userdata ";
        $data=$this->connection->query($q);
        return $data;
    }


    function getbookdetail($id){
        $q="SELECT * FROM book where id ='$id'";
        $data=$this->connection->query($q);
        return $data;
    }

    function userdetail($id){
        $q="SELECT * FROM userdata where id ='$id'";
        $data=$this->connection->query($q);
        return $data;
    }

    public function requestbook($userid, $bookid) {
        // Sanitize and validate inputs
        $userid = htmlspecialchars(trim($userid));
        $bookid = htmlspecialchars(trim($bookid));
    
        if (!filter_var($userid, FILTER_VALIDATE_INT) || !filter_var($bookid, FILTER_VALIDATE_INT)) {
            echo "Invalid User ID or Book ID.";
            exit();
        }
    
        // Prepare and execute query to get book details
        $stmt = $this->connection->prepare("SELECT bookname FROM book WHERE id = ?");
        $stmt->bind_param("i", $bookid);
        $stmt->execute();
        $stmt->bind_result($bookname);
        $stmt->fetch();
        $stmt->close();
    
        // Prepare and execute query to get user details
        $stmt = $this->connection->prepare("SELECT name, type FROM userdata WHERE id = ?");
        $stmt->bind_param("i", $userid);
        $stmt->execute();
        $stmt->bind_result($username, $usertype);
        $stmt->fetch();
        $stmt->close();
    
        // Determine the number of days based on user type
        $days = ($usertype == "student") ? 7 : (($usertype == "teacher") ? 21 : 0);
    
        // Insert request into the database
        $stmt = $this->connection->prepare("INSERT INTO requestbook (userid, bookid, username, usertype, bookname, issuedays) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssi", $userid, $bookid, $username, $usertype, $bookname, $days);
    
        if ($stmt->execute()) {
            header("Location: otheruser_dashboard.php?userlogid=$userid");
        } else {
            header("Location: otheruser_dashboard.php?msg=fail");
        }
    
        $stmt->close();
    }    


    function returnbook($id){
        $fine="";
        $bookava="";
        $issuebook="";
        $bookrentel="";

        $q="SELECT * FROM issuebook where id='$id'";
        $recordSet=$this->connection->query($q);

        foreach($recordSet->fetchAll() as $row) {
            $userid=$row['userid'];
            $issuebook=$row['issuebook'];
            $fine=$row['fine'];

        }
        if($fine==0){

        $q="SELECT * FROM book where bookname='$issuebook'";
        $recordSet=$this->connection->query($q);   

        foreach($recordSet->fetchAll() as $row) {
            $bookava=$row['bookava']+1;
            $bookrentel=$row['bookrent']-1;
        }
        $q="UPDATE book SET bookava='$bookava', bookrent='$bookrentel' where bookname='$issuebook'";
        $this->connection->exec($q);

        $q="DELETE from issuebook where id=$id and issuebook='$issuebook' and fine='0' ";
        if($this->connection->exec($q)){
    
            header("Location:otheruser_dashboard.php?userlogid=$userid");
         }
        //  else{
        //     header("Location:otheruser_dashboard.php?msg=fail");
        //  }
        }
        // if($fine!=0){
        //     header("Location:otheruser_dashboard.php?userlogid=$userid&msg=fine");
        // }
       

    }
    
    function issuereport(){
        $q="SELECT * FROM issuebook ";
        $data=$this->connection->query($q);
        return $data;
        
    }

    function requestbookdata(){
        $q="SELECT * FROM requestbook ";
        $data=$this->connection->query($q);
        return $data;
    }

    public function addnewuser($name, $password, $email, $type) {
        $this->name = htmlspecialchars(trim($name));
        $this->password = htmlspecialchars(trim($password));
        $this->email = htmlspecialchars(trim($email));
        $this->type = htmlspecialchars(trim($type));
    
        // Hash the password for security
        $hashed_password = password_hash($this->password, PASSWORD_BCRYPT);
    
        // Prepare SQL statement to prevent SQL injection
        $stmt = $this->connection->prepare("INSERT INTO userdata (name, email, pass, type) VALUES (?, ?, ?, ?)");
        
        // Bind parameters and execute the query
        if ($stmt) {
            $stmt->bind_param("ssss", $this->name, $this->email, $hashed_password, $this->type);
            
            if ($stmt->execute()) {
                header("Location: admin_service_dashboard.php?msg=New user added successfully");
            } else {
                header("Location: admin_service_dashboard.php?msg=Failed to register user");
            }
    
            $stmt->close();
        } else {
            header("Location: admin_service_dashboard.php?msg=Failed to prepare statement");
        }
    }

    public function deleteuserdata($id) {
        // Prepare the SQL query to prevent SQL injection
        $stmt = $this->connection->prepare("DELETE FROM userdata WHERE id = ?");
        
        if ($stmt) {
            // Bind the user ID as an integer to the prepared statement
            $stmt->bind_param("i", $id);
            
            // Execute the statement and check if the deletion was successful
            if ($stmt->execute()) {
                header("Location: admin_service_dashboard.php?msg=done");
            } else {
                header("Location: admin_service_dashboard.php?msg=fail");
            }
            
            $stmt->close();
        } else {
            header("Location: admin_service_dashboard.php?msg=fail");
        }
    }
    

    public function deletebook($id) {
        // Use prepared statements to prevent SQL injection
        $stmt = $this->connection->prepare("DELETE FROM book WHERE id = ?");
        
        if ($stmt) {
            // Bind the $id parameter to the prepared statement
            $stmt->bind_param("i", $id);
    
            // Execute the query
            if ($stmt->execute()) {
                // Redirect with success message
                header("Location:admin_service_dashboard.php?msg=Book deleted successfully");
            } else {
                // Redirect with failure message
                header("Location:admin_service_dashboard.php?msg=Failed to delete book");
            }
    
            // Close the statement
            $stmt->close();
        } else {
            // Redirect with preparation failure message
            header("Location:admin_service_dashboard.php?msg=Failed to prepare statement");
        }
    }

      // issue issuebookapprove
    public function issuebookapprove($book, $userselect, $days, $getdate, $returnDate, $redid) {
        // Sanitize input (if necessary) and assign them to local variables
        $book = htmlspecialchars(trim($book));
        $userselect = htmlspecialchars(trim($userselect));
        $days = intval($days);
        $getdate = htmlspecialchars($getdate);
        $returnDate = htmlspecialchars($returnDate);
    
        // Query to get book details
        $bookQuery = "SELECT * FROM book WHERE bookname = ?";
        $bookStmt = $this->connection->prepare($bookQuery);
        $bookStmt->execute([$book]);
        $bookDetails = $bookStmt->fetch();
    
        // Query to get user details
        $userQuery = "SELECT * FROM userdata WHERE name = ?";
        $userStmt = $this->connection->prepare($userQuery);
        $userStmt->execute([$userselect]);
        $userDetails = $userStmt->fetch();
    
        if ($userDetails && $bookDetails) {
            $issueid = $userDetails['id'];
            $issuetype = $userDetails['type'];
            $bookid = $bookDetails['id'];
            $bookname = $bookDetails['bookname'];
    
            $newbookava = $bookDetails['bookava'] - 1;
            $newbookrent = $bookDetails['bookrent'] + 1;
    
            // Update book availability and rent count
            $updateBookQuery = "UPDATE book SET bookava = ?, bookrent = ? WHERE id = ?";
            $updateBookStmt = $this->connection->prepare($updateBookQuery);
    
            if ($updateBookStmt->execute([$newbookava, $newbookrent, $bookid])) {
                // Insert into issuebook
                $issueBookQuery = "INSERT INTO issuebook (userid, issuename, issuebook, issuetype, issuedays, issuedate, issuereturn, fine) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, '0')";
                $issueBookStmt = $this->connection->prepare($issueBookQuery);
    
                if ($issueBookStmt->execute([$issueid, $userselect, $bookname, $issuetype, $days, $getdate, $returnDate])) {
                    // Delete from requestbook after issue
                    $deleteRequestQuery = "DELETE FROM requestbook WHERE id = ?";
                    $deleteRequestStmt = $this->connection->prepare($deleteRequestQuery);
    
                    if ($deleteRequestStmt->execute([$redid])) {
                        header("Location: admin_service_dashboard.php?msg=done");
                    } else {
                        header("Location: admin_service_dashboard.php?msg=Failed to delete request.");
                    }
                } else {
                    header("Location: admin_service_dashboard.php?msg=Failed to issue book.");
                }
            } else {
                header("Location: admin_service_dashboard.php?msg=Failed to update book info.");
            }
        } else {
            header("Location: index.php?msg=Invalid Credentials");
        }
    }
    
    
    // issue book
    public function issuebook($book, $userselect, $days, $getdate, $returnDate) {
        // Sanitize inputs to prevent security issues
        $this->book = htmlspecialchars($book);
        $this->userselect = htmlspecialchars($userselect);
        $this->days = intval($days);
        $this->getdate = $getdate;
        $this->returnDate = $returnDate;
    
        // Prepare and execute the query to find the book
        $q = "SELECT * FROM book WHERE bookname = ?";
        $stmtBook = $this->connection->prepare($q);
        $stmtBook->execute([$this->book]);
        $bookRecord = $stmtBook->fetch(PDO::FETCH_ASSOC);
    
        // If book exists, proceed
        if ($bookRecord) {
            $bookid = $bookRecord['id'];
            $newbookava = $bookRecord['bookava'] - 1;
            $newbookrent = $bookRecord['bookrent'] + 1;
    
            // Prepare and execute the query to find the user
            $q = "SELECT * FROM userdata WHERE name = ?";
            $stmtUser = $this->connection->prepare($q);
            $stmtUser->execute([$this->userselect]);
            $userRecord = $stmtUser->fetch(PDO::FETCH_ASSOC);
    
            // If user exists, proceed
            if ($userRecord) {
                $issueid = $userRecord['id'];
                $issuetype = $userRecord['type'];
    
                // Update the book's availability and rent count
                $q = "UPDATE book SET bookava = ?, bookrent = ? WHERE id = ?";
                $stmtUpdateBook = $this->connection->prepare($q);
                if ($stmtUpdateBook->execute([$newbookava, $newbookrent, $bookid])) {
    
                    // Insert into the issuebook table
                    $q = "INSERT INTO issuebook (userid, issuename, issuebook, issuetype, issuedays, issuedate, issuereturn, fine) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, '0')";
                    $stmtIssueBook = $this->connection->prepare($q);
                    if ($stmtIssueBook->execute([$issueid, $this->userselect, $this->book, $issuetype, $this->days, $this->getdate, $this->returnDate])) {
                        header("Location: admin_service_dashboard.php?msg=done");
                    } else {
                        header("Location: admin_service_dashboard.php?msg=fail");
                    }
                } else {
                    header("Location: admin_service_dashboard.php?msg=fail");
                }
            } else {
                header("Location: index.php?msg=Invalid User Credentials");
            }
        } else {
            header("Location: index.php?msg=Book Not Found");
        }
    }
    
}