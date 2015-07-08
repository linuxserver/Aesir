        <section id="disks" class="body">

            <h2>PRECLEAR - Available Disks</h2>

            <?php 
            //print_r($disk_extra);
            if(isset($disks) && !empty($disks)) { 
                foreach($disks as $disk) {
                    $disk_info = explode(" = ", $disk);
                    $disk_size = shell_exec('blockdev --getsize64 /dev/'.$disk_info[0]);
            ?>
                    <div class="inset-box preclear">
                        <img class="disk" src="/img/disk.png" alt="Parity" />
                         <div><?php echo $disk_info[1];?></div>
                       <div class="size"><?php echo format_bytes($disk_size);?></div>
                        <img class="ribbon" src="/img/green-ribbon.png" alt="Parity" />
                        <div class="disk-ref"><?php echo $disk_info[0];?></div>
                        <?php
                        if(isRunning($disk_info[0])) {
                            echo 'In progress';
                        } else {
                        ?>
                        <a class="redbutton button" href="/index.php/utilities/start_preclear/<?php echo $disk_info[0];?>/">Preclear Disk</a>
                        <?php } ?>
                    </div>
            <?php 
                }
            } else {
                echo 'There are currently no available disks to preclear';
            }?>
        </section>

