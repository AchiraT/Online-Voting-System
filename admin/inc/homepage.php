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
            color: white;
        }
        .btn-success:hover,
        .btn-success:focus,
        .btn-success:active {
            background-color: #b2011c;
            border-color: #b2011c;
            color: white;
            box-shadow: none;
        }
        .btn-view-results {
            background-color: #b2011c;
            border-color: #b2011c;
            color: white;
        }
        .btn-view-results:hover,
        .btn-view-results:focus,
        .btn-view-results:active {
            background-color: #b2011c;
            border-color: #b2011c;
            color: white;
            box-shadow: none;
        }
        .no-election {
            text-align: center;
            font-weight: bold;
            color: #b2011c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row my-3">  
            <div class="col-12">
                <h3 class="mb-3">Elections</h3>
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th scope="col"><i class="fas fa-list-ol"></i> S.No</th>
                            <th scope="col"><i class="fas fa-vote-yea"></i> Election Name</th>
                            <th scope="col"><i class="fas fa-users"></i> # Candidates</th>
                            <th scope="col"><i class="fas fa-calendar-alt"></i> Starting Date</th>
                            <th scope="col"><i class="fas fa-calendar-alt"></i> Ending Date</th>
                            <th scope="col"><i class="fas fa-info-circle"></i> Status</th>
                            <th scope="col"><i class="fas fa-cogs"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $fetchingData = mysqli_query($db, "SELECT * FROM elections") or die(mysqli_error($db)); 
                            $isAnyElectionAdded = mysqli_num_rows($fetchingData);

                            if($isAnyElectionAdded > 0) {
                                $sno = 1;
                                while($row = mysqli_fetch_assoc($fetchingData)) {
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
                                            <a href="index.php?viewResult=<?php echo $election_id; ?>" class="btn btn-sm btn-view-results">
                                                <i class="fas fa-eye"></i> View Results
                                            </a>
                                        </td>
                                    </tr>
                        <?php
                                }
                            } else {
                        ?>
                                <tr> 
                                    <td colspan="7" class="no-election">No elections added yet.</td>
                                </tr>
                        <?php
                            }
                        ?>
                    </tbody>    
                </table>
            </div>
        </div>
    </div>
</body>
</html>
