<?php include_once("./_header.php");
include_once("./_subHeader.php"); ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="divider text-success">
        <div class="divider-text text-danger">
            <i class="bx bx-star"></i>
            <i class="bx bx-star"></i>
            <i class="bx bx-star"></i>
        </div>
    </div>
    <div class="col-md-4 offset-md-4">
        <div class="card mb-4">
            <div class="card-body">

                <form action="../app/fileUpload.php" enctype="multipart/form-data" method="POST">
                    <div class="form-group">
                        <label for="uploadfile">File Title & Keyword</label>
                        <input type="text" class="form-control" name="fileTitle_Keyword" maxlength="55" placeholder="Enter File Title and Keyqords" required>
                    </div>
                    <div class="form-group">
                        <label for="uploadfile">Upload File</label>
                        <input type="file" class="form-control" name="fileUpload" id="fileUpload">
                    </div>
                    <div class="text-end">
                        <button type="submit" name="fileUploadBtn" class="btn btn-primary mt-3">Upload File</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8 offset-md-2">
        <div class="card md-4">
            <img id="uploadImg" src="" alt="">
        </div>
    </div>
</div>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#uploadImg').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#uploadImg").hide();
    $("#fileUpload").change(function() {
        readURL(this);
        $("#uploadImg").show();
    });
</script>
<?php include_once("./_footer.php"); ?>