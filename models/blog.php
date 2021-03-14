<?php

class BlogModel extends Model
{
    public function Index()
    {
        $this->query('SELECT COUNT(*) as nb FROM blog');
        $nbBlogsTemp = $this->resultSet();
        $nbBlogs = $nbBlogsTemp[0]['nb'];
        $perPage = 5;
        $nbPage = ceil($nbBlogs/$perPage);
        
        if(isset($_GET['page'])) {// && $$_GET['p']>0 && $_GET['p']<=$nbPage) {
            $currentPage = $_GET['page'];
            print('oui');
        }
        else {
            $currentPage = 1;
            print('non');
            print_r($_GET['page']);
        }
        
        $this->query('SELECT * FROM blog ORDER BY create_date DESC LIMIT '.(($currentPage-1)*$perPage).','.$perPage);
        $rows = $this->resultSet();

        for($i=1;$i<=$nbPage;$i++) {
            
                echo " <a href=\"blog?page=$i\">$i</a> /";
            
        }

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