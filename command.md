ALTER TABLE `category` ADD `whatsappGroup` VARCHAR(500) NULL DEFAULT NULL AFTER `categoryDate`, ADD `telegramChannel` VARCHAR(500) NULL DEFAULT NULL AFTER `whatsappGroup`, ADD `youtubeChannel` VARCHAR(500) NULL DEFAULT NULL AFTER `telegramChannel`, ADD `facebookPage` VARCHAR(500) NULL DEFAULT NULL AFTER `youtubeChannel`;

ALTER TABLE `post` ADD `color_name` VARCHAR(50) NULL DEFAULT NULL AFTER `post_date`;

CREATE TABLE `news_db`.`color` (`color_id` INT(191) NOT NULL AUTO_INCREMENT , `color_name` INT(50) NULL DEFAULT NULL , `generate_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`color_id`)) ENGINE = InnoDB;

ALTER TABLE `settings` ADD `websiteAbout` VARCHAR(150) NULL DEFAULT NULL; 

ALTER TABLE `post` ADD `last_update` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP;


   <br><br><br>
        <div class="footer">
                <table>
                        <thead>
                                <th>About</th>
                                <th>Quick Links</th>
                                <th>Latest News</th>
                        </thead>
                        <tbody>
                                <tr>
                                        <td>
                                                <ul>
                                                        <li><a href="<?= $web_kit['websiteUrl'] ?>" target="_blank"><?= strtoupper($web_kit['websitename']); ?></a><?= $web_kit['websiteAbout'] ?>
                                                        </li>
                                                        <li>
                                                                <a class="bg-green" target="_blank" href="<?= $web_kit['websiteUrl'] . "/" . $web_kit['whatsappGroup']; ?>"><i style="margin: 15px 0px;" class="fa-brands fa-whatsapp"></i></a>
                                                                <a class="bg-blue" target="_blank" href="<?= $web_kit['websiteUrl'] . "/" . $web_kit['facebookPage']; ?>"><i style="margin: 15px 0px;" class="fa-brands fa-facebook"></i></a>
                                                                <a class="bg-blue" target="_blank" href="<?= $web_kit['websiteUrl'] . "/" . $web_kit['telegramChannel']; ?>"><i style="margin: 15px 0px;" class="fa-brands fa-telegram"></i></a>
                                                                <a class="bg-red" target="_blank" href="<?= $web_kit['websiteUrl'] . "/" . $web_kit['youtubeChannel']; ?>"><i style="margin: 15px 0px;" class="fa-brands fa-youtube"></i></a>
                                                        </li>
                                                </ul>
                                        </td>
                                        <td>
                                                <ul>
                                                        <li><a href="" target="_blank" rel="noopener noreferrer">Home</a></li>
                                                        <li><a href="" target="_blank" rel="noopener noreferrer">About Us</a></li>
                                                        <li><a href="" target="_blank" rel="noopener noreferrer">Contact Us</a></li>
                                                        <li><a href="" target="_blank" rel="noopener noreferrer">Disclaimer</a></li>
                                                        <li><a href="" target="_blank" rel="noopener noreferrer">Privacy Policy</a></li>
                                                        <li><a href="" target="_blank" rel="noopener noreferrer">Site Map</a></li>
                                                </ul>
                                        </td>
                                        <td>
                                                <ul>
                                                        <?php $shortPost = mysqli_query($conn, "SELECT * FROM post WHERE postStatus ='Y' ORDER BY post_id DESC LIMIT 0,5");
                                                        if (mysqli_num_rows($shortPost) > 0) {
                                                                while ($shortPostDetails = mysqli_fetch_assoc($shortPost)) {
                                                                        $txt = cleanUrl($shortPostDetails['title']);
                                                                        $title = substr($shortPostDetails['title'], 0, 15) . "...";
                                                                        $postid = $shortPostDetails['post_id'];
                                                                        echo "<li><a href='{$web_kit['websiteUrl']}/post-details/{$txt}/{$postid}' target='_blank' rel='noopener noreferrer'>{$title}</a></li>";
                                                                }
                                                        } else {
                                                                echo "<li>No Recourd Found. </li>";
                                                        } ?></ul>
                                        </td>
                                </tr>
                        </tbody>
                </table>
        </div>

</div>