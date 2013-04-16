<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once(dirname(__FILE__)."/common.php");

buildTopPage("who");

buildContent();

buildBottomPage();

function buildContent(){
    ?>
    <div id="content">
        <div id="mask">
            <div id="main-container">
                <?php
                $arrayfiles = array();
                $extensions = array('.jpg','.jpeg','.png');
                $dirname = SLIDESHOW_DIR;
                if(file_exists($dirname)){
                        $handle = opendir($dirname);
                        while (false !== ($file = readdir($handle))) { 
                                if(is_file($dirname.$file)){
                                        $ext = strtolower(substr($file, strrpos($file, "."), strlen($file)-strrpos($file, ".")));
                                        if(in_array($ext,$extensions)){
                                                array_push($arrayfiles,$file);
                                        }
                                }
                        }
                        $handle = closedir($handle);
                }
                for($i=0; $i< count($arrayfiles); $i++){
                    if($i==0){
                        ?><div class="slide current"><img src="<?php echo SLIDESHOW_PATH .$arrayfiles[$i] ?>" /></div><?php
                    }else{
                        ?><div class="slide"><img src="<?php echo SLIDESHOW_PATH .$arrayfiles[$i] ?>" /></div><?php
                    }
                }
                ?>
            </div>
        </div>
        <div id="upper-shadow"><img src="<?php echo IMAGES_PATH . "upper_shadow.png" ?>" /></div>
        <div id="description">
            <!-- TODO contenuto -->
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed auctor semper scelerisque. Donec dui lacus, scelerisque eu sollicitudin et, condimentum quis diam. Morbi libero tortor, congue non feugiat in, luctus vitae odio. Donec risus mi, pulvinar vel aliquet in, volutpat eu erat. In eget sem leo, vitae scelerisque lorem. Nam aliquam lacinia ante ut mollis. Aliquam erat volutpat.</p>
            <p>Nunc malesuada varius risus et blandit. Nullam vel turpis ipsum, eu congue eros. Duis malesuada turpis quis ante scelerisque quis cursus orci elementum. Donec eleifend scelerisque luctus. Mauris vitae metus est, a pellentesque nulla. Donec mollis suscipit sodales. Sed luctus facilisis elementum. Curabitur felis nisi, ullamcorper sed malesuada nec, mollis gravida massa. Nunc felis arcu, aliquet at gravida ac, euismod at ante. Proin ante lorem, feugiat molestie commodo sed, fermentum vitae ante. Integer fermentum rutrum magna, eget faucibus ligula lobortis ac. Aliquam tristique scelerisque massa vestibulum facilisis. Vestibulum laoreet mauris et purus porta sodales. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>
            <p>Morbi sit amet felis erat. Nulla vitae lobortis lectus. Mauris sit amet diam metus, nec consequat mi. Pellentesque auctor ipsum id libero ullamcorper semper. Duis quis massa at arcu venenatis lobortis. Sed condimentum, felis sed placerat tempus, leo magna sodales magna, at auctor purus ligula vitae magna. Vestibulum eleifend ante non justo porttitor bibendum. Etiam a aliquet nunc. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec non ipsum lorem. Aenean a velit mauris.</p>
            <p>Etiam lacinia placerat vestibulum. Etiam euismod orci lacinia justo vehicula in cursus tortor ultricies. Suspendisse a justo a lectus consectetur porta. Mauris volutpat turpis eu arcu posuere ultricies. Duis sem nunc, pretium quis volutpat id, posuere in ante. Integer consequat leo pharetra nibh pharetra ut euismod massa tempus. Quisque in quam odio, a mollis nulla. Curabitur eget tristique libero. Vivamus iaculis, ligula vitae auctor tincidunt, tortor nisi vestibulum purus, at feugiat nisi sem ut turpis. Pellentesque at facilisis arcu. Pellentesque vitae diam id dolor rhoncus lobortis eget eu dolor. Suspendisse nec enim ante. Integer vitae nulla a tellus bibendum facilisis. Proin a purus turpis. Vestibulum sit amet placerat nisi. Nam vitae elit orci, sit amet euismod odio.</p>
        </div>
    </div>
    <?php
}