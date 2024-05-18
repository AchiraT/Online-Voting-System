<?php 
    if(isset($_GET['added']))
    {
?>
        <div class="alert alert-success my-3" role="alert">
            Election has been added successfully.
        </div>
<?php 
    }else if(isset($_GET['delete_id']))
    {
        $d_id = $_GET['delete_id'];
        mysqli_query($db, "DELETE FROM elections WHERE id = '". $d_id ."'") OR die(mysqli_error($db));
?>
       <div class="alert alert-danger my-3" role="alert">
            Election has been deleted successfully!
        </div>
<?php
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elections</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .table thead th {
            background-color: #343a40;
            color: #fff;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
        .btn-success {
            background-color: #b2011c;
            border-color: #b2011c;
        }
        .btn-success:hover {
            background-color: #511317;
            border-color: #511317;
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }
        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .no-election {
            text-align: center;
            font-weight: bold;
            color: #ff0000;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row my-3">
            <div class="col-12">
                <h3 class="mb-3">Add New Election</h3>
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" name="election_topic" placeholder="Election Topic" class="form-control" required />
                        </div>
                        <div class="form-group col-md-6">
                            <input type="number" name="number_of_candidates" placeholder="No of Candidates" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" onfocus="this.type='date'" name="starting_date" placeholder="Starting Date" class="form-control" required />
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" onfocus="this.type='date'" name="ending_date" placeholder="Ending Date" class="form-control" required />
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="addElectionBtn" id="addElectionBtn" class="btn btn-success">Add Election</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-12">
                <h3>Upcoming Elections</h3>
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Election Name</th>
                            <th scope="col">Candidates</th>
                            <th scope="col">Starting Date</th>
                            <th scope="col">Ending Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $fetchingData = mysqli_query($db, "SELECT * FROM elections") or die(mysqli_error($db)); 
                            $isAnyElectionAdded = mysqli_num_rows($fetchingData);

                            if($isAnyElectionAdded > 0)
                            {
                                $sno = 1;
                                while($row = mysqli_fetch_assoc($fetchingData))
                                {
                                    $election_id = $row['id'];
                        ?>
                                    <tr>
                                        <td><?php echo $sno++; ?></td>
                                        <td><?php echo $row['election_topic']; ?></td>
                                        <td><?php echo $row['no_of_candidates']; ?></td>
                                        <td><?php echo $row['starting_date']; ?></td>
                                        <td><?php echo $row['ending_date']; ?></td>
                                        <td><?php echo $row['status']; ?></td>
                                        <td> 
                                            <a href="http://localhost/phpmyadmin/index.php?route=/sql&pos=0&db=onlinevotingsystem&table=elections" class="btn btn-sm btn-warning">Edit</a>
                                            <button class="btn btn-sm btn-danger" onclick="DeleteData(<?php echo $election_id; ?>)">Delete</button>
                                        </td>
                                    </tr>
                        <?php
                                }
                            } else {
                        ?>
                                <tr> 
                                    <td colspan="7" class="no-election">No election is added yet.</td>
                                </tr>
                        <?php
                            }
                        ?>
                    </tbody>    
                </table>
            </div>
        </div>
    </div>

    <script>
        const DeleteData = (e_id) => 
        {
            let c = confirm("Do you really want to delete it?");
            if(c == true)
            {
                location.assign("index.php?addElectionPage=1&delete_id=" + e_id);
            }
        }

        document.getElementById('addElectionBtn').addEventListener('click', function() {
            this.classList.remove('btn-success');
            this.classList.add('btn-danger');
        });
    </script>

    <?php 
        if(isset($_POST['addElectionBtn']))
        {
            $election_topic = mysqli_real_escape_string($db, $_POST['election_topic']);
            $number_of_candidates = mysqli_real_escape_string($db, $_POST['number_of_candidates']);
            $starting_date = mysqli_real_escape_string($db, $_POST['starting_date']);
            $ending_date = mysqli_real_escape_string($db, $_POST['ending_date']);
            $inserted_by = $_SESSION['username'];
            $inserted_on = date("Y-m-d");

            $date1 = date_create($inserted_on);
            $date2 = date_create($starting_date);
            $diff = date_diff($date1, $date2);
            
            if((int)$diff->format("%R%a") > 0)
            {
                $status = "Inactive";
            } else {
                $status = "Active";
            }

            mysqli_query($db, "INSERT INTO elections(election_topic, no_of_candidates, starting_date, ending_date, status, inserted_by, inserted_on) VALUES('". $election_topic ."', '". $number_of_candidates ."', '". $starting_date ."', '". $ending_date ."', '". $status ."', '". $inserted_by ."', '". $inserted_on ."')") or die(mysqli_error($db));
    ?>
            <script> location.assign("index.php?addElectionPage=1&added=1"); </script>
    <?php
        }
    ?>
</body>
</html>
