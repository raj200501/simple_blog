<?php
require_once '../src/Model/Post.php';

class PostController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function index()
    {
        return Post::all($this->conn);
    }

    public function create($title, $content)
    {
        Post::create($this->conn, $title, $content);
    }

    public function edit($id)
    {
        return Post::find($this->conn, $id);
    }

    public function update($id, $title, $content)
    {
        Post::update($this->conn, $id, $title, $content);
    }

    public function delete($id)
    {
        Post::delete($this->conn, $id);
    }

    public function view($id)
    {
        return Post::find($this->conn, $id);
    }
}
?>
