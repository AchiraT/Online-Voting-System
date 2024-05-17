<?php 
    if(isset($_GET['added']))
    {
?>
        <div class="alert alert-success my-3" role="alert">
            Candidate has been added successfully.
        </div>
<?php 
    }else if(isset($_GET['largeFile'])) {
?>
        <div class="alert alert-danger my-3" role="alert">
            Candidate image is too large, please upload a smaller file (you can upload any image up to 2MB).
        </div>
<?php
    }else if(isset($_GET['invalidFile']))
    {
?>
        <div class="alert alert-danger my-3" role="alert">
            Invalid image type (Only .jpg, .png files are allowed).
        </div>
<?php
    }else if(isset($_GET['failed']))
    {
?>
        <div class="alert alert-danger my-3" role="alert">
            Image uploading failed, please try again.
        </div>
<?php
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Management</title>
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
        .no-election, .no-candidate {
            text-align: center;
            font-weight: bold;
            color: #ff0000;
        }
        .candidate_photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid #b2011c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row my-3">
            <div class="col-12">
                <h3 class="mb-3">Add New Candidates</h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <select class="form-control" name="election_id" required> 
                                <option value="">Select Election</option>
                                <?php 
                                    $fetchingElections = mysqli_query($db, "SELECT * FROM elections") OR die(mysqli_error($db));
                                    $isAnyElectionAdded = mysqli_num_rows($fetchingElections);
                                    if($isAnyElectionAdded > 0)
                                    {
                                        while($row = mysqli_fetch_assoc($fetchingElections))
                                        {
                                            $election_id = $row['id'];
                                            $election_name = $row['election_topic'];
                                            $allowed_candidates = $row['no_of_candidates'];

                                            // Now checking how many candidates are added in this election 
                                            $fetchingCandidate = mysqli_query($db, "SELECT * FROM candidate_details WHERE election_id = '". $election_id ."'") or die(mysqli_error($db));
                                            $added_candidates = mysqli_num_rows($fetchingCandidate);

                                            if($added_candidates < $allowed_candidates)
                                            {
                                    ?>
                                            <option value="<?php echo $election_id; ?>"><?php echo $election_name; ?></option>
                                    <?php
                                            }
                                        }
                                    }else {
                                ?>
                                        <option value="">Please add election first</option>
                                <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" name="candidate_name" placeholder="Candidate Name" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="file" name="candidate_photo" class="form-control" required />
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" name="candidate_details" placeholder="Candidate Details" class="form-control" required />
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="addCandidateBtn" id="addCandidateBtn" class="btn btn-success">Add Candidate</button>
                    </div>
                </form>
            </div>   
        </div>
        <div class="row my-3">
            <div class="col-12">
                <h3>Candidate Details</h3>
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Photo</th>
                            <th scope="col">Name</th>
                            <th scope="col">Details</th>
                            <th scope="col">Election</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $fetchingData = mysqli_query($db, "SELECT * FROM candidate_details") or die(mysqli_error($db)); 
                            $isAnyCandidateAdded = mysqli_num_rows($fetchingData);

                            if($isAnyCandidateAdded > 0)
                            {
                                $sno = 1;
                                while($row = mysqli_fetch_assoc($fetchingData))
                                {
                                    $election_id = $row['election_id'];
                                    $fetchingElectionName = mysqli_query($db, "SELECT * FROM elections WHERE id = '". $election_id ."'") or die(mysqli_error($db));
                                    $execFetchingElectionNameQuery = mysqli_fetch_assoc($fetchingElectionName);
                                    $election_name = $execFetchingElectionNameQuery['election_topic'];

                                    $candidate_photo = $row['candidate_photo'];
                                    $candidate_id = $row['id'];
                        ?>
                                    <tr>
                                        <td><?php echo $sno++; ?></td>
                                        <td><img src="<?php echo $candidate_photo; ?>" class="candidate_photo" /></td>
                                        <td><?php echo $row['candidate_name']; ?></td>
                                        <td><?php echo $row['candidate_details']; ?></td>
                                        <td><?php echo $election_name; ?></td>
                                        <td> 
                                            <a href="http://localhost/phpmyadmin/index.php?route=/sql&pos=0&db=onlinevotingsystem&table=candidate_details" class="btn btn-sm btn-warning">Edit</a>
                                            <button class="btn btn-sm btn-danger" onclick="DeleteData(<?php echo $candidate_id; ?>)">Delete</button>
                                        </td>
                                    </tr>   
                        <?php
                                }
                            }else {
                        ?>
                                    <tr> 
                                        <td colspan="7" class="no-candidate">No any candidate is added yet.</td>
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
        const DeleteData = (c_id) => 
        {
            let a = confirm("Do you really want to delete it?");
            if(a == true)
            {
                location.assign("index.php?addCandidatePage=1&delete_id=" + c_id);
            }
        }

        // Change button color when clicked
        document.getElementById('addCandidateBtn').addEventListener('click', function() {
            this.classList.remove('btn-success');
            this.classList.add('btn-danger');
        });
    </script>

    <?php 
        if(isset($_POST['addCandidateBtn']))
        {
            $election_id = mysqli_real_escape_string($db, $_POST['election_id']);
            $candidate_name = mysqli_real_escape_string($db, $_POST['candidate_name']);
            $candidate_details = mysqli_real_escape_string($db, $_POST['candidate_details']);
            $inserted_by = $_SESSION['username'];
            $inserted_on = date("Y-m-d");

            // Photograph Logic Starts
            $targetted_folder = "../assets/images/candidate_photos/";
            $candidate_photo = $targetted_folder . rand(111111111, 99999999999) . "_" . rand(111111111, 99999999999) . $_FILES['candidate_photo']['name'];
            $candidate_photo_tmp_name = $_FILES['candidate_photo']['tmp_name'];
            $candidate_photo_type = strtolower(pathinfo($candidate_photo, PATHINFO_EXTENSION));
            $allowed_types = array("jpg", "png", "jpeg");        
            $image_size = $_FILES['candidate_photo']['size'];

            if($image_size < 2000000) // 2 MB
            {
                if(in_array($candidate_photo_type, $allowed_types))
                {
                    if(move_uploaded_file($candidate_photo_tmp_name, $candidate_photo))
                    {
                        // inserting into db
                        mysqli_query($db, "INSERT INTO candidate_details(election_id, candidate_name, candidate_details, candidate_photo, inserted_by, inserted_on) VALUES('". $election_id ."', '". $candidate_name ."', '". $candidate_details ."', '". $candidate_photo ."', '". $inserted_by ."', '". $inserted_on ."')") or die(mysqli_error($db));

                        echo "<script> location.assign('index.php?addCandidatePage=1&added=1'); </script>";

                    }else {
                        echo "<script> location.assign('index.php?addCandidatePage=1&failed=1'); </script>";                    
                    }
                }else {
                    echo "<script> location.assign('index.php?addCandidatePage=1&invalidFile=1'); </script>";
                }
            }else {
                echo "<script> location.assign('index.php?addCandidatePage=1&largeFile=1'); </script>";
            }

            // Photograph Logic Ends
        }
    ?>
</body>
</html>
