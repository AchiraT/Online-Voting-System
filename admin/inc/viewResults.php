<?php 
    $election_id = $_GET['viewResult'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Results</title>
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
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #511317;
            border-color: #1e7e34;
        }
        .no-election, .no-votes {
            text-align: center;
            font-weight: bold;
            color: #ff0000;
        }
        .candidate_photo {
            width: 50px;
            height: 50px;
            border-radius: 50px;
            border: 2px solid #b2011c;
        }
        .bg-red {
            background-color: #b2011c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row my-3">
            <div class="col-12">
                <h3> Election Results </h3>
                <?php 
                    $fetchingActiveElections = mysqli_query($db, "SELECT * FROM elections WHERE id = '". $election_id ."'") or die(mysqli_error($db));
                    $totalActiveElections = mysqli_num_rows($fetchingActiveElections);

                    if($totalActiveElections > 0) {
                        while($data = mysqli_fetch_assoc($fetchingActiveElections)) {
                            $election_id = $data['id'];
                            $election_topic = $data['election_topic'];    
                ?>
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="4" class="bg-red text-white"><h5> ELECTION TOPIC: <?php echo strtoupper($election_topic); ?></h5></th>
                                </tr>
                                <tr>
                                    <th> Photo </th>
                                    <th> Candidate Details </th>
                                    <th> # of Votes </th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                $fetchingCandidates = mysqli_query($db, "SELECT * FROM candidate_details WHERE election_id = '". $election_id ."'") or die(mysqli_error($db));

                                while($candidateData = mysqli_fetch_assoc($fetchingCandidates)) {
                                    $candidate_id = $candidateData['id'];
                                    $candidate_photo = $candidateData['candidate_photo'];

                                    // Fetching Candidate Votes 
                                    $fetchingVotes = mysqli_query($db, "SELECT * FROM votings WHERE candidate_id = '". $candidate_id . "'") or die(mysqli_error($db));
                                    $totalVotes = mysqli_num_rows($fetchingVotes);
                            ?>
                                    <tr>
                                        <td> <img src="<?php echo $candidate_photo; ?>" class="candidate_photo"> </td>
                                        <td><?php echo "<b>" . $candidateData['candidate_name'] . "</b><br />" . $candidateData['candidate_details']; ?></td>
                                        <td><?php echo $totalVotes; ?></td>
                                    </tr>
                            <?php
                                }
                            ?>
                            </tbody>
                        </table>
                <?php
                        }
                    } else {
                        echo "<div class='no-election'>No any active election.</div>";
                    }
                ?>
                <hr>
                <h3>Voting Details</h3>
                <?php 
                    $fetchingVoteDetails = mysqli_query($db, "SELECT * FROM votings WHERE election_id = '". $election_id ."'");
                    $number_of_votes = mysqli_num_rows($fetchingVoteDetails);

                    if($number_of_votes > 0) {
                        $sno = 1;
                ?>
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Voter Name</th>
                                    <th>Contact No</th>
                                    <th>Voted To</th>
                                    <th>Date </th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                <?php
                        while($data = mysqli_fetch_assoc($fetchingVoteDetails)) {
                            $voters_id = $data['voters_id'];
                            $candidate_id = $data['candidate_id'];
                            $fetchingUsername = mysqli_query($db, "SELECT * FROM users WHERE id = '". $voters_id ."'") or die(mysqli_error($db));
                            $isDataAvailable = mysqli_num_rows($fetchingUsername);
                            $userData = mysqli_fetch_assoc($fetchingUsername);
                            if($isDataAvailable > 0) {
                                $username = $userData['username'];
                                $NIC = $userData['NIC'];
                            } else {
                                $username = "No_Data";
                                $NIC = $userData['NIC'];
                            }

                            $fetchingCandidateName = mysqli_query($db, "SELECT * FROM candidate_details WHERE id = '". $candidate_id ."'") or die(mysqli_error($db));
                            $isDataAvailable = mysqli_num_rows($fetchingCandidateName);
                            $candidateData = mysqli_fetch_assoc($fetchingCandidateName);
                            if($isDataAvailable > 0) {
                                $candidate_name = $candidateData['candidate_name'];
                            } else {
                                $candidate_name = "No_Data";
                            }
                ?>
                                <tr>
                                    <td><?php echo $sno++; ?></td>
                                    <td><?php echo $username; ?></td>
                                    <td><?php echo $NIC; ?></td>
                                    <td><?php echo $candidate_name; ?></td>
                                    <td><?php echo $data['vote_date']; ?></td>
                                    <td><?php echo $data['vote_time']; ?></td>
                                </tr>
                <?php
                        }
                        echo "</tbody></table>";
                    } else {
                        echo "<div class='no-votes'>No vote detail is available!</div>";
                    }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
