<?php include_once("./_header.php");
include_once("./_subHeader.php");
$selectQ = mysqli_query($conn, "SELECT * FROM filelist ORDER BY list_id DESC");

?>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="divider text-success">
        <div class="divider-text text-danger">
            <i class="bx bx-star"></i>
            <i class="bx bx-star"></i>
            <i class="bx bx-star"></i>
        </div>
    </div>
    <div class="container">
        <div class="card p-4">
            <h5 class="card-header"><a class="btn btn-outline-primary" href="new-upload-file.php"><i class='bx bxs-file-plus' ></i> New File</a></h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover" id="fileList">
                    <thead>
                        <tr>
                            <th>No:</th>
                            <th>File Titile</th>
                            <th>File Links</th>
                            <th>File Upload Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php $serial= 1; if(mysqli_num_rows($selectQ)>0){while($fetched = mysqli_fetch_assoc($selectQ)){ ?>
                        <tr>
                            <td>
                                <i class="bx bxs-file bx-sm text-danger"></i>
                            </td>
                            <td><?= substr($fetched['file_title'],0,20);?></td>
                            <td><?=$url."/uploaded/".$fetched['file_name'];?></td>
                            <td><span class="badge bg-label-primary me-1"><?=$fetched['file_last_update'];?></span></td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php $serial++;} }else{
                            echo "<tr><td colspan='5'><center><h2>No Recourd Found.</h2></center></td></tr>";
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once("./_footer.php"); ?>