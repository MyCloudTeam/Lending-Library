<?php
    include_once '../includes/db_connect.php';
    include_once '../includes/functions.php';
    include_once '../includes/friendship.php';
     
    if(session_status() != PHP_SESSION_ACTIVE){
        sec_session_start(); // Our custom secure way of starting a PHP session.
    }

    if(login_check($mysqli) != TRUE){
        echo json_encode(array('success'=>0, 'error_id'=>401, 'error_msg'=>'Not authorized to view this page'));
        die();
    }

    if(isset($_GET['query_string'])){
        $results = array();

        if(isset($_GET['criterion'])){
            $criterion = $_GET['criterion'];
        }
        else{
            $criterion = NULL;
        }

        // choose to search between categories
        if(isset($_GET['search_cat'])){
            // search for users and push into results
            if($_GET['search_cat']=='users'){
                // if($u_res=search_users($mysqli, $_GET['query_string']))
                //     array_push($results, array('users'=>$u_res);
                array_push($results, array('users'=>search_users($mysqli, $_GET['query_string'])));
            }

            // search for books and push into results
            if($_GET['search_cat']=='books'){
                // if($b_res=search_books($mysqli, $_GET['query_string'], $criterion))
                //     array_push($results, search_users($mysqli, $_GET['query_string']));
                array_push($results, array('books'=>search_books($mysqli, $_GET['query_string'], $criterion)));
            }
        }
        else{
            array_push($results, array('users'=>search_users($mysqli, $_GET['query_string'])));
            array_push($results, array('books'=>search_books($mysqli, $_GET['query_string'], $criterion)));
        }

        echo json_encode(array('success'=>1, 'results'=>$results)); //results
    }

    function search_books($mysqli, $query_string, $criterion=NULL){

        if(isset($criterion)){
            if($criterion == "title"){
                $query = "SELECT * FROM books WHERE book_title LIKE '%$query_string%' AND privacy!='private'";
            }
            elseif($criterion == "author"){
                $query = "SELECT * FROM books WHERE book_author LIKE '%$query_string%' AND privacy!='private'";
            }
            elseif($criterion == "isbn"){
                $query = "SELECT * FROM books WHERE book_isbn LIKE '%$query_string%' AND privacy!='private'";
            }
            elseif($criterion == "uploader"){
                //echo $query_string;
                $query = "SELECT books.* FROM books natural join users WHERE user_name LIKE '%$query_string%' AND privacy!='private'";
            }
            else{
                echo json_encode(array('success'=>0, 'error_id'=>4, 'error_msg'=>'The given criterion does NOT exist'));
                die();
            }
        }
        else{
            $query = "(SELECT books.* FROM books WHERE book_title LIKE '%$query_string%'
                        OR book_author LIKE '%$query_string%'
                        OR book_isbn LIKE '%$query_string%'
                        AND privacy!='private') UNION
                        (SELECT books.* FROM books natural join users WHERE user_name LIKE '%$query_string%' AND privacy!='private')";
        }

        $res = array();
        // echo $query;
        if($b_sl = $mysqli->query($query)){
            while($bk = $b_sl->fetch_assoc()){
                $bk['result_type'] = 'book';
                array_push($res,$bk);
            }
            if(isset($res)){
                // echo $c;
                return $res;
            }
            else{
                echo json_encode(array('success'=>0, 'error_id'=>1, 'error_msg'=>'No Results Found'));
            }
        }
        else{
            echo json_encode(array('success'=>0, 'error_id'=>2, 'error_msg'=>'Query Failed'));
        }
        return NULL;
    }

    function search_users($mysqli, $query_string){
        $res = array();
        if($b_sl = $mysqli->query("SELECT user_id, user_name, user_pic FROM users WHERE user_name LIKE '%$query_string%'")){
            while($bk = $b_sl->fetch_assoc()){
                $bk['result_type'] = 'user';
                array_push($res,$bk);
            }
            if(isset($res)){
                // echo $c;
                return $res;
            }
            else{
                echo json_encode(array('success'=>0, 'error_id'=>1, 'error_msg'=>'No Results Found'));
            }
        }
        else{
            echo json_encode(array('success'=>0, 'error_id'=>2, 'error_msg'=>'Query Failed'));
        }
        return NULL;
    }

?>