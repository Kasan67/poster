<?php

class Db
{
    private $db;
    private static $_instance;
    private function __construct()
    {
        $config = parse_ini_file('database.ini');
        $this->db = new mysqli($config['hosts'], $config['username'], $config['password'], $config['database']);
    }
    public static function getInstance()
    {
        if(!self::$_instance){
            self::$_instance = new self();
            return self::$_instance;
        }else{
            return self::$_instance;
        }
    }

    private function __clone(){}
    public function getConnect()
    {
        return $this->db;
    }
}

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>GuestBook</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    </head>

    <body>

        <div class="container-fluid">
            <div class="col-md-10">
                <p>Hello</p>
                <?php
                $db = Db::getInstance(); 
                $link = $db->getConnect(); 
                $result = $link->query("SELECT * FROM items WHERE status = 2"); 
                $items = mysqli_fetch_all($result, MYSQLI_ASSOC); 
                if(is_array($items)){ ?>
                    <table class="table table-hover table-bordered display" id="myTable" data-page-length='3'>
                        <thead>
                            <tr>
                                <th class="col-md-1">User</th>
                                <th class="col-md-1">Title</th>
                                <th class="col-md-4">Description</th>
                                <th class="col-md-2">Link</th>
                                <th class="col-md-2">Publicated_to</th>
                            </tr>
                        </thead>
                        <?php foreach($items as $text){ ?>
                            <tr>
                                <td>
                                    <?php echo $text['user_id'];?>
                                </td>
                                <td>
                                    <?php echo $text['title'];?>
                                </td>
                                <td>
                                    <?php echo $text['descr']; ?>
                                </td>
                                <td>
                                    <a href="<?php echo $text['link']; ?>">Link to post</a>
                                </td>
                                <td>
                                    <?php echo $text['publicated_to']; ?>
                                </td>
                            </tr>
                            <?php } } ?>
                    </table>
            </div>
            <div class="col-md-2">
                <form id="form" method="post" action="index.php  " enctype="multipart/form-data">
                    <p>E-mail*</p>
                    <p>
                        <input type="email" id="email" name="email" class="email form-control" required/>
                    </p>
                    <p>Title*</p>
                    <p>
                        <input type="text" id="title" name="title" class="form-control" required/>
                    </p>
                    <p>Text*</p>
                    <p>
                        <textarea id="text" class="message form-control" name="text" required></textarea>
                    </p>
                    <p> * - required section</p>
                    <p>
                        <button type="button" class="btn btn-danger">Cancel</button>
                        <button type="submit" class="btn btn-success" value="send" name="send">Send</button>
                    </p>
                </form>
                <?php
                if(!empty($_POST['email'])){
                    $email = addslashes($_POST['email']);
                    $getUser = $link->query("SELECT id from users where email='$email'");
                    if($getUser->num_rows < 1){
                        $link->query("INSERT INTO users (email) VALUE ('$email')");
                        $getUser = $link->query("SELECT id from users where email='$email'");
                    }
                    $items = mysqli_fetch_all($getUser, MYSQLI_ASSOC);
                    $setUserId = $items[0]['id'];
                    $title = addslashes($_POST['title']);
                    $text = addslashes($_POST['text']);
                    
                    $publicated_to = date('Y-m-d G:i:s', mktime(date("G"), date("i"), date("s"), date("m")+1, date("d"),   date("Y"))); 
                    $sqlInsert = $link->query("INSERT INTO items (user_id, status, title, link, descr, publicated_to) VALUE ('$setUserId', 2,  '$title', '/testsite/?this_user_id=$setUserId', '$text', '$publicated_to')");
                }
                ?>
            </div>
        </div>
    </body>