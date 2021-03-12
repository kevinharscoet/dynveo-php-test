<?php

class BlogModel extends Model
{
    public function Index()
    {
        $this->query('SELECT * FROM blog ORDER BY create_date DESC');
        $rows = $this->resultSet();
        return $rows;
    }

    public function add()
    {
        // Sanitize Post
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if ($post['submit']) {

            if ($post['title'] == '' || $post['body'] == '' || $post['link'] == '') {
                Messages::setMsg('Please complete all fields', 'error');
                return;
            }

            // Insert into MySQL
            $this->query('INSERT INTO blog(title, body, link, id_user,create_date) VALUES(:title, :body, :link, :id_user, :create_date)');
            $this->bind(':title', $post['title']);
            $this->bind(':body', $post['body']);
            $this->bind(':link', $post['link']);
            $this->bind(':id_user', 1);
            $this->bind(':create_date', date('Y-m-d'));
            $this->execute();

            // Verify
            if ($this->lastInsertId()) {
                // Redirect
                header('Location: ' . ROOT_URL . 'blog');
            }
        }
        return;
    }   
}