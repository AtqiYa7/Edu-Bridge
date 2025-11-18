<?php
include("inc/connection.inc.php");

ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
    $user = "";
    $utype_db = "";
} else {
    $user = $_SESSION['user_login'];
    $result = $con->query("SELECT * FROM user WHERE id='$user'");
    $get_user_name = $result->fetch_assoc();
    $uname_db = $get_user_name['fullname'];
    $utype_db = $get_user_name['type'];
}

//time ago convert
include_once("inc/timeago.php");
$time = new timeago();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edu Bridge</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="css/footer.css" rel="stylesheet" type="text/css" media="all" />
    <!-- homemenu removed -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
</head>
<body class="body1">
<div>
    <header class="header">
        <div class="header-cont">
            <?php include 'inc/banner.inc.php'; ?>
        </div>
    </header>

    <!-- Top Navigation -->
    <div class="topnav">
        <a class="active navlink" href="index.php" style="margin: 0px 0px 0px 100px;">Newsfeed</a>
        <a class="navlink" href="search.php">Search Tutor</a>
        <?php 
        if($utype_db != "teacher") {
            echo '<a class="navlink" href="postform.php">Post</a>';
        }
        ?>
        <a class="navlink" href="#contact">Contact</a>
        <a class="navlink" href="#about">About</a>

        <div style="float: right;">
            <table>
                <tr>
                    <?php
                    if($user != "") {
                        $resultnoti = $con->query("SELECT * FROM applied_post WHERE post_by='$user' AND student_ck='no'");
                        $resultnoti_cnt = $resultnoti->num_rows;
                        if($resultnoti_cnt == 0){
                            $resultnoti_cnt = "";
                        } else {
                            $resultnoti_cnt = '('.$resultnoti_cnt.')';
                        }

                        echo '<td><a class="navlink" href="notification.php">Notification'.$resultnoti_cnt.'</a></td>
                              <td><a class="navlink" href="profile.php?uid='.$user.'">'.$uname_db.'</a></td>
                              <td><a class="navlink" href="logout.php">Logout</a></td>';
                    } else {
                        echo '<td><a class="navlink" href="login.php">Login</a></td>
                              <td><a class="navlink" href="registration.php">Register</a></td>';
                    }
                    ?>
                </tr>
            </table>
        </div>
    </div>

    <!-- Newsfeed -->
    <div class="nbody" style="margin: 0px 100px; overflow: hidden;">
        <div class="nfeedleft">
            <?php
            $todaydate = date("Y-m-d"); //Current date in proper format

            $query = $con->query("SELECT * FROM post ORDER BY id DESC");
            while ($row = $query->fetch_assoc()) {
                $post_id = $row['id'];
                $postby_id = $row['postby_id'];
                $sub = str_replace(",", ", ", $row['subject']);
                $class = str_replace(",", ", ", $row['class']);
                $salary = $row['salary'];
                $location = str_replace(",", ", ", $row['location']);
                $p_university = $row['p_university'];
                $post_time = $row['post_time'];
                $deadline = $row['deadline'];
                $medium = str_replace(",", ", ", $row['medium']);

                $query1 = $con->query("SELECT * FROM user WHERE id='$postby_id'");
                $user_fname = $query1->fetch_assoc();
                $post_uname = $user_fname['fullname'];
                $post_pro_pic = $user_fname['user_pic'];
                $post_gender = $user_fname['gender'];

                if($post_pro_pic == ""){
                    $post_pro_pic = ($post_gender == "male") ? "malepic.png" : "femalepic.png";
                } else {
                    if(!file_exists("image/profilepic/".$post_pro_pic)){
                        $post_pro_pic = ($post_gender == "male") ? "malepic.png" : "femalepic.png";
                    }
                }

                // Convert post_time and deadline to timestamps
                $post_timestamp = strtotime($post_time);
                $deadline_timestamp = strtotime($deadline);

                echo '
                <div class="nfbody">
                    <div class="p_head">
                        <div style="float: right;">';
                            if($user != '' && $utype_db == 'student'){
                                // students cannot see deadline button
                            } else {
                                if($deadline_timestamp < strtotime($todaydate)){
                                    echo '<input type="submit" class="sub_button" style="margin: 0px; background-color: #a76d6d; cursor: default;" value="Deadline Over" />';
                                } else {
                                    $resultpostcheck = $con->query("SELECT * FROM applied_post WHERE post_id='$post_id' AND applied_by='$user'");
                                    if($resultpostcheck->num_rows > 0){
                                        echo '<input type="button" class="sub_button" style="margin: 0px; background-color: #a76d6d; cursor: default;" value="Already Applied" />';
                                    } else {
                                        echo '<form action="viewpost.php?pid='.$post_id.'" method="post">
                                            <input type="submit" class="sub_button" style="margin: 0px;" value="Apply" />
                                        </form>';
                                    }
                                }
                            }
                echo '</div>
                        <div>
                            <img src="image/profilepic/'.$post_pro_pic.'" width="41px" height="41px">
                        </div>
                        <div class="p_nmdate">
                            <h4>'.$post_uname.'</h4>
                            <h5 style="color: #757575;">
                                <a class="c_ptime" href="viewpost.php?pid='.$post_id.'">'.$time->time_ago($post_time).'</a> &nbsp;|&nbsp; Deadline: '.date("Y-m-d", $deadline_timestamp).'
                            </h5>
                        </div>
                    </div>

                    <div class="p_body">
                        <div class="itemrow">
                            <div class="itemrowdiv1"><p><label>Subject: </label></p></div>
                            <div class="itemrowdiv2"><span>'.$sub.'</span></div>
                        </div>
                        <div class="itemrow">
                            <div class="itemrowdiv1"><label>Class: </label></div>
                            <div class="itemrowdiv2"><span>'.$class.'</span></div>
                        </div>
                        <div class="itemrow">
                            <div class="itemrowdiv1"><label>Medium: </label></div>
                            <div class="itemrowdiv2"><span>'.$medium.'</span></div>
                        </div>
                        <div class="itemrow">
                            <div class="itemrowdiv1"><label>Salary: </label></div>
                            <div class="itemrowdiv2"><span>'.$salary.'</span></div>
                        </div>
                        <div class="itemrow">
                            <div class="itemrowdiv1"><label>Location: </label></div>
                            <div class="itemrowdiv2"><span>'.$location.'</span></div>
                        </div>
                        <div class="itemrow">
                            <div class="itemrowdiv1"><label>Preferred University: </label></div>
                            <div class="itemrowdiv2"><span>'.$p_university.'</span></div>
                        </div>
                    </div>
                </div>';
            }
            ?>
        </div>
        <div class="nfeedright">
            <!-- Right side content if needed -->
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="js/jquery-3.2.1.min.js"></script>
<!-- homemenu script removed -->
<script src="js/topnavfixed.js"></script>
</body>
</html>
