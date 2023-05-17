<?php

use PHPUnit\Framework\TestCase;

class UserCreateAccountTest extends TestCase
{
    private $db;
    private $email;
    private $username;
    private $password;

    protected function setUp(): void
    {
        // Set up a test database connection
        $host = '127.0.0.1'; 
        $user = 'root'; // MySQL user name
        $pass = ''; // MySQL password
        $db = 'db_32741829'; // MySQL database name

        $this->db = new mysqli($host, $user, $pass, $db);
       
        // Set up test data
        $this->email = "test@example.com";
        $this->username = "test_user";
        $this->password = md5("test_password");
        // include("../db.php");
    }

    protected function tearDown(): void
    {
        // Close the database connection
        $this->db->close();
    }

    public function testUserCreateAccount()
    {
        // Insert a new user into the test database
        $sql = "INSERT INTO User (email, username, password,profilePicture ,pictureType) VALUES ('{$this->email}', '{$this->username}', '{$this->password}' , 'null' , 'null')";
        $this->db->query($sql);

        // Check if the user was inserted successfully
        $sql = "SELECT * FROM User WHERE email = '{$this->email}' AND username = '{$this->username}'";
        $result = $this->db->query($sql);
        $this->assertEquals(1, $result->num_rows);

        // Check if the username or email already exists
        $sql = "SELECT * FROM User WHERE username = '{$this->username}' OR email = '{$this->email}'";
        $result = $this->db->query($sql);
        $this->assertGreaterThanOrEqual(1, $result->num_rows);

        // Remove the test user from the database
        $sql = "DELETE FROM User WHERE email = '{$this->email}' AND username = '{$this->username}'";
        $this->db->query($sql);
    }
}
