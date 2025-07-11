This guide outlines the steps to set up and run the PHP application locally using XAMPP. It provides instructions on setting up the database and switching between a local and online database.

**Step 1: Install and Configure XAMPP
    -Download XAMPP: Download and install XAMPP from https://www.apachefriends.org/.
    -Start XAMPP:
        -Open the XAMPP Control Panel and start the following services:
            -Apache
            -MySQL

**Step 2: Set Up the Application
    -Place the Project in XAMPP's Root Directory:
        -Copy your project folder to the 'htdocs' directory.
        -For example: C:\xampp\htdocs\Website CC.

    -Set Up the Database:
        -Create a database named computer_complex:
            -Open http://localhost/phpmyadmin/ in your browser.
            -Click on the Databases tab.
            -Enter computer_complex as the database name and click Create.
            -Import the Database:
                -Select the computer_complex database.
                -Click on Import.
                -Choose the provided database file in the following directory in project folder (database/zquery/computer_complex.sql) and click Go.

        -Configure the Database Connection:
            -Navigate to database/DBConn.php in your project.
                -By default, the code connects to the local database. Ensure the following lines are active (uncommented):
                -php code :
                    $host = 'localhost';
                    $username = 'root'; // Default XAMPP username
                    $password = '';     // Default XAMPP password
                    $database = 'computer_complex';

        **If you want to use the online database, comment out the local database code and uncomment the lines below it**5

**Step 3: Run the Application
    -Start the Application:
        -Open your browser and navigate to: http://localhost/Website CC/   //Rember this is the folder name of the project in htdocs
            -Verify Functionality:
            -Ensure all pages load properly and interact with the database as expected.
            -If any errors occur, check the XAMPP logs for Apache and MySQL.

**Troubleshooting
    *1. Cannot Access Localhost*
        -Ensure Apache and MySQL services are running in XAMPP.
        -Confirm that no other application is using port 80 or 443:
        -Open XAMPP Control Panel, click Config, and change Apache’s ports if needed.

    *2. Database Errors*
        -Error: Unknown database 'computer_complex'
            -Verify the database was created and named correctly.
            -Re-import the database file into phpMyAdmin.
        
        -Error: Access denied for user 'root'@'localhost'
            -Ensure the default username (root) and password (empty) are used in DBConn.php.
            -If you’ve set a MySQL root password, update it in the DBConn.php file.

    *3. Online Database Issues*
        -Error: Unable to connect to the online database.
            -Confirm the credentials in DBConn.php match your online database.
            -Verify your online database allows remote connections.
