<?php

class UserModel extends Model
{
    public function register()
    {
        // Sanitize Post
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);


        $password = md5($post['password']);

        if ($post['submit']) {
            if ($post['name'] == '' || $post['email'] == '' || $post['password'] == '') {
                Messages::setMsg('Please complete all fields', 'error');
                return;
            }
            
            $this->query('SELECT * FROM users WHERE email=:email');
            $this->bind(':email', $post['email']);

            $row = $this->single();

            if ($row){
                Messages::setMsg('Email already use', 'error');
                return;
            }
                
            // Insert into MySQL
            $this->query('INSERT INTO users(name, email, password) VALUES(:name, :email, :password)');
            $this->bind(':name', $post['name']);
            $this->bind(':email', $post['email']);
            $this->bind(':password', $password);
            $this->execute();

            // Verify
            if ($this->lastInsertId()) {
                Messages::setMsg('Successfully registered', 'error');
            }
        }
        return;
    }

    public function login()
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $password = md5($post['password']);

        if ($post['submit']) {
            // Compare login
            $this->query('SELECT * FROM users WHERE email=:email AND password=:password');
            $this->bind(':email', $post['email']);
            $this->bind(':password', $password);

            $row = $this->single();

            if ($row) {
                $_SESSION['is_logged_in'] = true;
                $_SESSION['user_data'] = array(
                    "id" => $row['id'],
                    "name" => $row['name'],
                    "email" => $row['email']
                );
                header('Location: ' . ROOT_URL . 'blog');
            } else {
                Messages::setMsg('Incorrect login', 'error');
            }
        }
        return;
    }
}