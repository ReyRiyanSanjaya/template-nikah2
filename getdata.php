<!-- komen seksi dengan php  -->
<?php
include 'koneksi.php';
$sql = "SELECT author, comment, tanggal FROM komen";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
        // echo "id: " . $row["author"]. " - Name: " . $row["comment"]. " " . $row["tanggal"]. "<br>";
?>
        <ul id="wdp-container-comment-64448" class="wdp-container-comments wdp-order-DESC  wdp-has-7-comments wdp-multiple-comments" data-order="DESC" style="display: block;">
            <li class="comment even thread-even depth-1 wdp-item-comment" id="wdp-item-comment-13558" data-likes="0">
                <!--.wdp-comment-avatar-->
                <div class="wdp-comment-avatar">
                    <img alt="c" src="https://avatar.oxro.io/avatar.svg?name=<?php echo $row["author"] ?>&amp;background=random&amp;length=2&amp;caps=1&amp;fontSize=200&amp;bold=true">
                </div>
                <!--.wdp-comment-avatar-->
                <div class="wdp-comment-content">
                    <div class="wdp-comment-info">
                        <a class="wdp-commenter-name" title="asas"><?php echo $row["author"] ?></a>
                        <span class="wdp-post-author"><i class="fas fa-check-circle"></i> </span>
                        <br>
                        <span class="wdp-comment-time">
                            <i class="far fa-clock"></i>
                            <?php echo $row["tanggal"] ?>
                        </span>
                    </div>
                    <!--.wdp-comment-info-->
                    <div class="wdp-comment-text">
                        <p><?php echo $row["comment"] ?></p>
                    </div>
                </div>

            </li>
        </ul>
<?php
    }
} else {
    echo '<lottie-player src="https://assets5.lottiefiles.com/private_files/lf30_rc6evhf4.json"  background="transparent"  speed="1"  style="width: 300px; height: 300px;"  loop autoplay></lottie-player>';
}
?>
<!-- komen seksi  -->