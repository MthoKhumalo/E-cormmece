ReadMe: Setting Up and Running Unit Tests Using PHPUnit in VS Code
This guide explains how to set up and run unit tests for your PHP project using PHPUnit in VS Code. It provides two options: setting up from scratch or using pre-configured files. Troubleshooting tips are included for common issues.

    **Option 1: Setting Up PHPUnit from Scratch**
     -Step 1: Prerequisites
        -Install PHP: Ensure PHP 8.0.30 or higher is installed.

        If using XAMPP, PHP is included. Note its location (e.g., C:\xampp\php).
            -Set Up PHP in System PATH:
                -Open Command Prompt (CMD) and type:
                    -bash
                        -Copy code: php -v

        If you see a version error or unrecognized command:
            -Add PHP to the PATH:
                -Go to Environment Variables on your system.
                -Add the XAMPP PHP path (e.g., C:\xampp\php) to the system PATH variable.
                -Restart CMD and check again.
                
        Install Composer:
        Download Composer from https://getcomposer.org/.
            -Run the installer and ensure it uses your PHP installation. Check Composer installation:
                -bash
                    -Copy code: composer -v
            
        -Step 2: Install PHPUnit
            -Navigate to your project directory:
                -bash
                    -Copy code: cd path/to/your/project

            -Require PHPUnit using Composer:
                -bash
                    -Copy code: composer require --dev phpunit/phpunit

            -Verify installation:
                -bash
                    -Copy code: ./vendor/bin/phpunit --version

     -Step 3: Set Up PHPUnit
        -Create a phpunit.xml file in your project root:
            -xml
                -Copy code:
                    <?xml version="1.0" encoding="UTF-8"?>
                    <phpunit bootstrap="bootstrap.php"
                            colors="true"
                            stopOnFailure="false"
                            verbose="true">

                        <!-- Test Suite Definitions -->
                        <testsuites>
                        
                            <testsuite name="Unit Tests">
                                <directory suffix="Test.php">./tests/Unit</directory>
                            </testsuite>

                            <testsuite name="Admin Tests">
                                <directory suffix="Test.php">./tests/adminTests</directory>
                            </testsuite>

                            <testsuite name="Integration Tests">
                                <directory suffix="Test.php">./tests/Integration</directory>
                            </testsuite>

                        </testsuites>

                        <!-- Environment Variables -->
                        <php>
                            <env name="APP_ENV" value="testing"/>
                            <env name="DB_CONNECTION" value="sqlite"/>
                            <env name="DB_DATABASE" value=":memory:"/>
                            <env name="APP_DEBUG" value="true"/>
                        </php>

                    </phpunit>

        -Create the test directories:
            -bash
                -Copy code: 
                    mkdir -p tests/Unit tests/Integration
                    Write your test classes in these directories, ensuring they follow the naming convention *Test.php.

     -Step 4: Run Tests
        -To run all tests:
            -bash
                -Copy code: ./vendor/bin/phpunit

        -To run a specific test suite:
            -bash
                -Copy code: ./vendor/bin/phpunit --testsuite "Unit Tests"
        
        -To run a specific test file:
            -bash
                -Copy code: ./vendor/bin/phpunit tests/Unit/ProductGroupingTest.php

    **Option 2: Using Pre-configured Files**
     -If you receive pre-configured files, follow these steps:

     -Download Project Files:
        -Place the project in your desired directory (e.g., C:\xampp\htdocs\Website CC).

     -Ensure Dependencies Are Installed:
        -Navigate to the project directory:
            -bash
                -Copy code: cd path/to/your/project

     -Install dependencies using Composer:
        -bash
            -Copy code: composer install

     -Set Up PHPUnit:
        -Confirm that the phpunit.xml file exists in the project root.
        -Ensure the vendor directory is present. If not, rerun composer install.

    -Run Tests:
        -Use the same commands as in Option 1.

    ***Troubleshooting Common Issues***

    **1. Old Composer Version Not Matching PHP**
    -Update Composer:
        2-bash
            -Copy code: composer self-update

    -If Composer still doesn’t match your PHP version, update PHP itself or ensure Composer uses the correct PHP path:
        -bash
            -Copy code: composer --version

    **2. PHPUnit Errors**
    -Error: Command "phpunit" not found.
        -Ensure you’re running the command from the project root.
    
    -Use the full path:
        -bash
            -Copy code: ./vendor/bin/phpunit
    
    -Error: PHP version mismatch.
        -Update your PHP version or ensure your PATH points to the correct PHP.
    
    -Error: Test class not found.
        -Ensure the test file and class name match PHPUnit naming conventions.
    
    **3. XAMPP-Specific Issues**
        -If PHP isn’t recognized, ensure the XAMPP PHP path is added to your system PATH.
        -Restart CMD after making changes to the PATH.

    *File Structure EXAMPLE*
        Project Root
        │
        ├── composer.json
        ├── phpunit.xml
        ├── vendor/
        │
        ├── tests/
        │   ├── Unit/
        │   │   ├── ExampleTest.php
        │   │   └── AdminTest.php
        │   ├── Integration/
        │       ├── AdminOrderWorkflowTest.php
        │       └── AdminLoginIntegrationTest.php
        │
        
        *Open your powershell and run the following code:*
         - ./vendor/bin/phpunit --testsuite "Unit Tests"
         - ./vendor/bin/phpunit --testsuite "Admin Tests"
         - ./vendor/bin/phpunit

![alt text](image.png)

