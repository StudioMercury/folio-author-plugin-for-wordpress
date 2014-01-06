<?php 
    $articleService = DPSFolioAuthor_Article::getInstance();
    $article = $articleService->article($_POST["articleparent"]);
?>

<form class="text-left checkbox-list">
    
    <div class="danger alert warning text-center">
        <br />
        <i class="fa fa-warning"></i> <b>Warning:</b> This action can not be undone. <br /> Please verify your choice before choice before syncing the renditions.
        <br /><br />
    </div>

    <input type="hidden" name="action" value="sync_renditions"/>
    
    <div class="diagram hidden">
        <div class="rendition-from"><i class="fa fa-file"></i></div>
        <div class="action"><i class="fa fa-arrow-right"></i></div>
        <div class="rendition-to"><i class="fa fa-file-o"></i></div>
    </div>
    
    <br /><br /><br /><br />
    
    <div class="row">
        
        <div class="four columns text-center">
            <label><i class="fa fa-file"></i> Source</label>
            <br />
            <div class="field">
                <div class="picker">
                    <select name="origin">
                        <option value="<?php echo $article["localID"];?>">Original Article</option>
                        <?php foreach($article["renditions"] as $rendition): ?>
                        <?php
                            $folioService = DPSFolioAuthor_Folio::getInstance();
                            $folio = $folioService->folio( $rendition["folio"] );
                        ?>
                        <?php if( !is_wp_error($folio) ): ?>
                        <option value="<?php echo $rendition["localID"];?>"><?php echo $folio["device"]["name"];?> (<?php echo $folio["meta"]["folioName"];?>)</option>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="four columns text-center">
            <label>Action <i class="fa fa-arrow-right"></i> </label>
            <br />
            <div class="field">
                <div class="picker">    
                    <select name="fields">
                        <!--<option value="meta">Override Metadata</option>-->
                        <option value="content">Override Content</option>
                        <!--<option value="all">Override ALL</option>-->
                    </select>
                </div>
            </div>
        </div>
        
        <div class="four columns text-center">
            <label><i class="fa fa-file-o"></i> Destination</label>
            <br />
            <div class="field">
                <div class="picker">    
                    <select name="toUpdate">
                        <option value="ALL">All Renditions</option>
                        <option value="<?php echo $article["localID"];?>">Original Article</option>
                        <?php foreach($article["renditions"] as $rendition): ?>
                        <?php
                            $folioService = DPSFolioAuthor_Folio::getInstance();
                            $folio = $folioService->folio( $rendition["folio"] );
                        ?>
                        <?php if( !is_wp_error($folio) ): ?>
                        <option value="<?php echo $rendition["localID"];?>"><?php echo $folio["device"]["name"];?> (<?php echo $folio["meta"]["folioName"];?>)</option>
                        <?php endif; ?>
                        <?php endforeach; ?>

                    </select>
                </div>
            </div>
        </div>
    
    </div>
    
    <br /><br />
    
    <div class="text-center">
        <div class="medium primary btn"><a data-action="sync_renditions" value="Sync Rendtions"><i class="fa fa-refresh"></i>&nbsp; Sync</a></div>
    </div>
    
</form>