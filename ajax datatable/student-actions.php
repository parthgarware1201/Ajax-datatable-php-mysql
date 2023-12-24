<?php 
include('connection.php');

if (isset($_POST["action"])) {
    if ($_POST["action"] == "insert") {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];

        $sql = "INSERT INTO `CSE` (`name`,`email`,`address`,`phone`) values ('$name', '$email', '$address', '$phone' )";
        $query= mysqli_query($con,$sql);
        $lastId = mysqli_insert_id($con);
        if($query ==true)
        {
        
            $data = array(
                'status'=>'true',
            
            );

            echo json_encode($data);
        }
        else
        {
            $data = array(
                'status'=>'false',
            
            );

            echo json_encode($data);
        } 
    }
    else if($_POST["action"]=="delete"){
        $id = $_POST['id'];
        $sql = "DELETE FROM CSE WHERE id='$id'";
        $delQuery =mysqli_query($con,$sql);
        if($delQuery==true)
        {
            $data = array(
                'status'=>'success',
            
            );

            echo json_encode($data);
        }
        else
        {
            $data = array(
                'status'=>'failed',
            
            );

            echo json_encode($data);
        } 
    }
    else if($_POST["action"]=="select"){
        $output= array();
        $sql = "SELECT * FROM CSE";

        $totalQuery = mysqli_query($con,$sql);
        $total_all_rows = mysqli_num_rows($totalQuery);

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'address',
            4 => 'phone',
        );

        if(isset($_POST['search']['value']))
        {
            $search_value = $_POST['search']['value'];
            $sql .= " WHERE name like '%".$search_value."%'";
            $sql .= " OR email like '%".$search_value."%'";
            $sql .= " OR address like '%".$search_value."%'";
            $sql .= " OR phone like '%".$search_value."%'";
        }

        if(isset($_POST['order']))
        {
            $column_name = $_POST['order'][0]['column'];
            $order = $_POST['order'][0]['dir'];
            $sql .= " ORDER BY ".$columns[$column_name]." ".$order."";
        }
        else
        {
            $sql .= " ORDER BY id ASC";
        }

        if($_POST['length'] != -1)
        {
            $start = $_POST['start'];
            $length = $_POST['length'];
            $sql .= " LIMIT  ".$start.", ".$length;
        }	

        $query = mysqli_query($con,$sql);
        $count_rows = mysqli_num_rows($query);
        $data = array();
        while($row = mysqli_fetch_assoc($query))
        {
            $sub_array = array();
            $sub_array[] = $row['id'];
            $sub_array[] = $row['name'];
            $sub_array[] = $row['email'];
            $sub_array[] = $row['address'];
            $sub_array[] = $row['phone'];
            $sub_array[] = '<a href="#" data-id="'.$row['id'].'"  class="btn btn-info btn-sm editbtn" >Edit</a>  <a href="#" data-id="'.$row['id'].'"  class="btn btn-danger btn-sm deleteBtn" >Delete</a>';
            $data[] = $sub_array;
        }

        $output = array(
            'draw'=> intval($_POST['draw']),
            'recordsTotal' =>$count_rows ,
            'recordsFiltered'=>   $total_all_rows,
            'data'=>$data,
        );
        echo  json_encode($output);
    }
    else if($_POST["action"]=="update"){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $id = $_POST['id'];

        $sql = "UPDATE `CSE` SET  `name`='$name' , `email`= '$email', `address`='$address',  `phone`='$phone' WHERE id='$id' ";
        $query= mysqli_query($con,$sql);
        $lastId = mysqli_insert_id($con);
        if($query ==true)
        {
        
            $data = array(
                'status'=>'true',
            
            );

            echo json_encode($data);
        }
        else
        {
            $data = array(
                'status'=>'false',
            
            );

            echo json_encode($data);
        } 
    }
}