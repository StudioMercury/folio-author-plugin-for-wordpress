<?php 
    global $post;
    $post_id = $post->ID;
?>

<div class="gumby">
    <div class="row">
    
        <div class="seven columns centered">
        <div class="row">
        
            <div class="columns twelve">
                <section class="tabs pill">
                    <ul class="tab-nav">
                        <li class="active" onclick="jQuery('#postdivrich').addClass('normal').removeClass('mobile tablet');">
                            <a href="#"><i class="fa fa-desktop" style="font-size: 1.25em;"></i> Normal View</a>
                        </li>
                        <li onclick="jQuery('#postdivrich').addClass('mobile').removeClass('normal tablet');">
                            <a href="#"><i class="fa fa-mobile" style="font-size: 1.5em;"></i> Mobile View</a>
                        </li>
                        <li onclick="jQuery('#postdivrich').addClass('tablet').removeClass('mobile normal');">
                            <a href="#"><i class="fa fa-tablet" style="font-size: 1.5em;"></i> Tablet View</a>
                        </li>
                    </ul>
                </section>
            </div>
                
        </div>
        </div>
        
    </div>
</div>

<br /><br />