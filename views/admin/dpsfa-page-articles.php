<?php
    $articleService = DPSFolioAuthor_Article::getInstance();
    $articles = $articleService->get_articles();
?>
<div class="gumby">
<div id="dialog" title="Dialog Window"><p></p></div>

<div id="wpbody-content" aria-label="Main content" tabindex="0">
    <div class="wrap row">

    <!-- PLUGIN MESSAGING -->
    <div id="dps-message">
        <br />
		<div class="message">
             <h2>
                <!-- <img class="dps-icon" src="http://placehold.it/50x50"> -->
                Here are all of your Articles. <br />
    			You can <div class="medium normal btn"><a href="post-new.php?post_type=dpsfa_article"><i class="fa fa-plus"></i> Create a New Article</a></div>
    			or <div class="medium normal btn"><a data-action="open_box_import_article"><i class="fa fa-files-o"></i> Import a Post as Article</a> </div>
		    </h2>
		</div>
	</div>


    
    <br /><br /><br />
    
    

    <!-- ARTICLE LIST -->
    <?php if( $articles ): ?>

    <table id="article-renditions" class="tablesorter">
    
    	<thead >
    		<tr>
    			<th width="5%" class="text-center">
    			    Preview
                </th>
    			<th width="15%">
    			    Article Name
    			    <i class="fa fa-sort-desc"></i>
    			    <i class="fa fa-sort-asc"></i>    			    
                </th>
    			<th width="15%">
    			    Article Title
    			    <i class="fa fa-sort-desc"></i>
    			    <i class="fa fa-sort-asc"></i>    			    
    			</th>
    			<th width="20%">
    			    Description
    			    <i class="fa fa-sort-desc"></i>
    			    <i class="fa fa-sort-asc"></i>    			    
    			</th>
    			<th width="10%">
    			    Section
    			    <i class="fa fa-sort-desc"></i>
    			    <i class="fa fa-sort-asc"></i>
    			</th>
    			<th width="10%">
    			    Template
    			    <i class="fa fa-sort-desc"></i>
    			    <i class="fa fa-sort-asc"></i>    			    
    			</th>
    			<th width="10%">
    			    Renditions
    			    <i class="fa fa-sort-desc"></i>
    			    <i class="fa fa-sort-asc"></i>    			    
    			</th>
    			<th width="13%"></th>
    			<th width="2%"></th>
    		</tr>
    	</thead>


        <tbody class="sortable article-view">
        <?php foreach ($articles as $article ): ?>
        

            <tr onclick="">
    			
    			<td width="5%" class="text-center">
        			<img src="<?php echo $article["preview"]["url"]; ?>" width="25" height="25" />
    			</td>
    			
    			<td width="15%">
        			<?php echo $article["meta"]["name"]; ?>
    			</td>
    			
    			<td width="20%">
        			<?php echo $article["meta"]["title"]; ?>
    			</td>
    			
    			<td width="10%">
        			<?php echo $article["meta"]["description"]; ?>
    			</td>
    			
    			<td width="10%">
        			<?php echo $article["meta"]["section"]; ?> 
    			</td>
    			
    			<td width="10%">
        			template
    			</td>    	
    			
    			<td width="10%">
        			<span class="light badge"><?php echo count(($article["renditions"])) ?></span>
    			</td>    					
    			
    			<td class="text-right" width="13%">
    			    <div class="article-actions">
			        <?php if( $article["linked"] ): ?>
			            <div class="medium default btn">
			                <a href="<?php echo get_edit_post_link($article["localID"]);?>" title="Edit Article"><i class="icon-pencil"></i>
			                <?php if($article["renditions"]) : ?><?php endif; ?>
			                </a>
			            </div>
			        <?php else: ?>
			            <div class="medium default btn">
			                <a title="Link Folio" data-action="<?php echo $article["linked"] ? "" : "link_folio";?>"  data-folio="<?php echo $article["localID"]; ?>">
			                    <i class="fa fa-link"></i> Link Article
			                </a>
			             </div>
			        <?php endif;?>
			            &nbsp; 
    			        <div class="medium default btn">
    			            <a data-action="delete_article" data-article="<?php echo $article["localID"]; ?>"><i class="fa fa-trash-o"></i></a>
    			        </div>       
    			    </div> 			
    			</td>
    		
    			<td width="2%"></td>     			
    			
    			
            </tr>
            
            <?php /*
            <?php if($article["renditions"]) : ?>           
                <?php foreach( $article["renditions"] as $rendition ): ?>
                <tr class="child-rendition">
                
        			<td class="text-center">
            			<img src="<?php echo $rendition["preview"]["url"]; ?>" width="25" height="25" />
        			</td>
        			
        			<td>
            			<?php echo $rendition["meta"]["name"]; ?>
        			</td>
        			
        			<td>
            			<?php echo $rendition["meta"]["title"]; ?>
        			</td>
        			
        			<td>
            			<?php echo $rendition["meta"]["description"]; ?>
        			</td>
        			
        			<td>
            			<?php echo $rendition["meta"]["section"]; ?> 
        			</td>
        			
        			<td>
            			template
        			</td>    	
        			
        			<td>
            			<span class="light badge"><?php echo count(($rendition["renditions"])) ?></span>
        			</td>    					
        			
        			<td class="text-right">
			            <div class="medium default btn">
			                <a href="<?php echo get_edit_post_link($rendition["localID"]);?>" title="Edit Article"><i class="icon-pencil"></i>
			                <?php if($rendition["renditions"]) : ?><?php endif; ?>
			                </a>
			            </div>
    			        <div class="medium default btn">
    			            <a data-action="delete_article" data-article="<?php echo $rendition["localID"]; ?>"><i class="fa fa-trash-o"></i></a>
    			        </div>        			
        			</td>
        		
        			<td></td>            
                
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            */?>

        <?php endforeach; ?>
        </tbody>
    
    
    <?php else: ?>
    </table>
    
    
        <h5 class="text-center">No articles found. Use the actions above to get started.</h5>
        
        
    <?php endif; ?>
    <!-- INFINITE SCROLL MORE -->



    </div>
</div>

<script type="text/javascript" src="<?php echo DPSFA_URL.'/js/mustache/mustache.js'; ?>"></script>
</div>

<script>

    jQuery("#article-renditions").tablesorter(); 

</script>


     
            
