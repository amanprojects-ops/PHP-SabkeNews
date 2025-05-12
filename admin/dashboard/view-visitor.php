<?php include_once "_header.php"; include_once "_subHeader.php";

    $data = file_get_contents("../../visitor_logs.txt");
    $data = explode("\n", $data);
    $data = array_filter($data);
    $data = array_map(function($item) {
        return json_decode($item, true);
    }, $data);
    // $data = array_values($data);
    // $data = array_reverse($data);
    // $data = array_slice($data, 0, 10);
    // echo "<pre>";
    // print_r($data);
    // echo "</pre>";
    // die();
    ?>
<!-- Content -->
<div class="container-fluid flex-grow-1 container-p-y">

    <!-- Layout Demo -->
    <div class="container my-5">

        <div class="divider text-success">
            <div class="divider-text text-danger">
                <i class="bx bx-star"></i>
                <i class="bx bx-star"></i>
                <i class="bx bx-star"></i>
            </div>
        </div>
        <div class="card">
            <!-- <h5 class="card-header">Hoverable rows</h5> -->
            <div class="card-header border-bottom">
                <h5 class="card-title">Visitor Lists</h5>

            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>IP Address</th>
                            <th>Location</th>
                            <th>Date & Time</th>
                            <th>Browser</th>
                            <th>ISP</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php 
                        $serial = 1;
                        foreach($data as $visitor) {
                        ?>
                        <tr>
                            <td><i class="fab fa-angular fa-lg text-danger me-3"></i>
                                <strong><?php echo $serial; ?></strong>
                            </td>
                            <td><strong><?php echo $visitor['public_ip']; ?></strong></td>
                            <td>
                                <?php 
                                $location = $visitor['public_ip_details']['city'] . ', ' . 
                                           $visitor['public_ip_details']['region'] . ', ' . 
                                           $visitor['public_ip_details']['country']; 
                                echo $location;
                                ?>
                            </td>
                            <td><span class="badge bg-label-primary me-1"><?php echo date("d M, Y h: s :i", strtotime($visitor['timestamp'])); ?></span>
                            </td>
                            <td><?php echo $visitor['browser']; ?></td>
                            <td><?php echo $visitor['public_ip_details']['isp']; ?></td>
                        </tr>
                        <?php $serial++; } ?>
                    </tbody>
                </table>
            </div>
            <?php                
                $total_records = count($data);
                $limit = 10;
                $total_pages = ceil($total_records / $limit);
                
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                $starting_limit = ($page - 1) * $limit;
                
                // Update the data array to show only current page records
                $data = array_slice($data, $starting_limit, $limit);
            ?>
            <div class="card-footer text-muted">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php
                            if ($page > 1) {
                                echo '<li class="page-item prev"><a class="page-link" href="?page=' . ($page - 1) . '"><i class="tf-icon bx bx-chevrons-left"></i></a></li>';
                            }

                            for ($i = 1; $i <= $total_pages; $i++) {
                                if ($i == $page) {
                                    $active = "active";
                                } else {
                                    $active = "";
                                }
                                echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                            }
                            if ($total_pages > $page) {
                                echo '<li class="page-item next"><a class="page-link" href="?page=' . ($page + 1) . '"><i class="tf-icon bx bx-chevrons-right"></i></a></li>';
                            }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
        <!--/ Layout Demo -->
    </div>
</div>

<!-- / Content -->
<?php include_once "_footer.php"; ?>