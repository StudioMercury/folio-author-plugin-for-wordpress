<?php    
    $paged = (isset( $_GET['paged'] )) ? $_GET['paged'] : 1;
    
    $associatedFolio = isset($_GET['folio']) ? $_GET['folio'] : null;
    $articleService = DPSFolioAuthor_Article::getInstance();
    $articleQuery = $articleService->get_articles(array(
        'parent'    => 0,
        'filter'    => 'local', 
        'limit' => 20,
        'order' => 'DESC',
        'returnQuery' => true,
        'paged' => $paged,
        'folioID'   => empty($_GET['issue']) ? null : $_GET['issue'],
    ));
    
    $pagination = $articleService->get_articles_pagination(array(
        'paged' => $paged,
        'originalQuery' => $articleQuery
    ));
        
    $articles = $articleService->get_articles_from_query($articleQuery);
?>

<div class="gumby">
<form id="article-list" action="?<?php echo $_SERVER['QUERY_STRING']; ?>" method="POST">

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
    			<!--or <div class="medium normal btn"><a data-action="open_box_import_article"><i class="fa fa-files-o"></i> Import a Post as Article</a> </div>-->
		    </h2>
		</div>
	</div>

    <br /><br /><br />
    
    
    <div class="row">
    
        <!-- FILTERING -->
        <div id="filter" class="seven columns">
            
            
                <div class="three columns">
                    
                    <!-- ARTICLE FILTER -->
                    <div class="input-label">Filter</div>
                        <div class="field">
                          <div class="picker">
                            <select onchange="filterIssue(jQuery(this).val())">
                                <?php
                				    $folioObj = DPSFolioAuthor_Folio::getInstance();
                                    $folios = $folioObj->get_folios( array(
                                        'limit' => -1,
                                        'parentOnly' => true
                                    ));
                				?>
                				
                                <option value="#" disabled <?php echo (!isset($_GET['issue'])) ? "selected" : ""; ?>>Filter by Issue</option>
                                <option value="0" <?php echo (isset($_GET['issue']) && $_GET['issue'] == 0) ? "selected" : ""; ?>>All Issues</option>
                                <?php foreach($folios as $folio): ?>
                                    <option value='<?php echo $folio["localID"]; ?>' <?php echo (isset($_GET['issue']) && $_GET['issue'] == $folio["localID"]) ? "selected" : ""; ?> ><?php echo $folio["meta"]["folioName"]; ?></option>
                                <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                
                </div>
                
                <!-- ARTICLE ACTIONS -->
                <div class="nine columns">
                    <div class="input-label">Bulk Actions</div>
                    
                    <div class="field">

                          <div class="picker">
                            <select id="bulk-action" name="bulk-action">
                                <option value="#" disabled selected>Select an action</option>
                                <option value="bulk_assign_articles">Assign to an Issue</option>
                                <option value="bulk_delete_articles">Delete</option>
                            </select>
                          </div>
                    
                    
        
                        <!-- Issue List -->
                        <?php
                            $folioService = DPSFolioAuthor_Folio::getInstance();
                            $folios = $folioObj->get_folios( array(
                                'limit' => -1,
                                'parentOnly' => true
                            ));
                        ?>
                        
                          <div class="picker" id="assign-issue" style="display:none">
                            <select name="assign-issue">
                                <option disabled selected>Select an Issue</option>
                            <?php foreach( $folios as $folio ): ?>
                                <option value="<?php echo $folio["localID"];?>"><?php echo $folio["meta"]["folioName"]; ?></option>
                            <?php endforeach; ?>
                            </select>
                          </div>
                        
                        <script>
                        jQuery("#bulk-action").change(function(){
                            if( jQuery(this).val() ){
                                jQuery("#bulk_action").show();
                            }else{
                                jQuery("#bulk_action").hide();
                            }
                            
                            if( jQuery(this).val() == "bulk_assign_articles" ){
                                jQuery("#assign-issue").show();
                            }else{
                                jQuery("#assign-issue").hide();
                            }
                        });
                        </script>
                    
        
                        <div id="bulk_action" class="medium primary btn hidden" data-action="bulk_action" data-form="#article-list" style="display:none; vertical-align:top"><a href="#">Do Action</a></div>
                
                    </div>
                    
                </div>
            
        </div>
        
        <!-- PAGINATION -->
        <div id="pagination" class="five columns text-right">
            <?php echo $pagination; ?>
        </div>
    
    </div>

    <!-- ARTICLE LIST -->
    <?php if( $articles ): ?>

    <table id="article-renditions" class="tablesorter">
    	<thead>
    		<tr>
    		    <th width="7%" class="text-center">
    				<span data-action="select_all" data-boxes="#article-renditions">Check All</span>
                </th>
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
    			<!--
    			<th width="20%">
    			    Description
    			    <i class="fa fa-sort-desc"></i>
    			    <i class="fa fa-sort-asc"></i>    			    
    			</th>
    			-->
    			<th width="10%">
    			    Section
    			    <i class="fa fa-sort-desc"></i>
    			    <i class="fa fa-sort-asc"></i>
    			</th>
    			<th width="10%">
    			    Issue
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
    			    <div class="field">
        			    <label class="checkbox one columns" for="<?php echo $article["localID"];?>">
                            <input id="<?php echo $article["localID"];?>" type="checkbox" value="<?php echo $article["localID"];?>" name="articles[]">
                            <span></span> 
                        </label>
                    </div>
    			</td>
    			
    			<td width="5%" class="text-center">
        			<img src="<?php echo $article["preview"]["url"]; ?>" width="25" height="25" />
    			</td>
    			
    			<td width="15%">
        			<?php echo $article["meta"]["name"]; ?>
    			</td>
    			
    			<td width="20%">
        			<?php echo $article["meta"]["title"]; ?>
    			</td>
    			
    			<!--
    			<td width="10%">
        			<?php echo $article["meta"]["description"]; ?>
    			</td>
    			-->
    			
    			<td width="10%">
        			<?php echo $article["meta"]["section"]; ?> 
    			</td>
    			
    			<td width="10%">
        			<?php 
        			    if($article["folio"]){
            			    $folioObj = DPSFolioAuthor_Folio::getInstance();
            			    $folio = $folioObj->folio($article["folio"]);
            			    if( !is_wp_error($folio) ){
                                echo $folio["meta"]["folioName"];
            			    }else{
                			    echo "Folio Deleted";
            			    }
        			    }
                    ?>
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
            
        <?php endforeach; ?>
        </tbody>
    
    
    <?php else: ?>
    </table>
    
    
    <?php if( isset( $_GET['issue'] ) && $_GET['issue'] != 0 ): ?>
        <h5 class="text-center">No articles found for the selected issue.</h5>
    <?php else: ?>
        <h5 class="text-center">No articles found. Use the actions above to get started.</h5>
    <?php endif; ?>
        
        
    <?php endif; ?>

    </div>
</div>

</form>
</div>

<script>
    function replaceQueryParam(param, newval, search) {
        var regex = new RegExp("([?;&])" + param + "[^&;]*[;&]?")
        var query = search.replace(regex, "$1").replace(/&$/, '')
        return (query.length > 2 ? query + "&" : "?") + param + "=" + newval
    }
    
    function filterIssue( issue ){
        var str = window.location.search;
            str = replaceQueryParam('issue', issue, str);
            str = replaceQueryParam('paged', 1, str)
        window.location = window.location.pathname + str
    }
    jQuery("#article-renditions").tablesorter({
        //sortList: [[1,0]]
        headers: { 
            0: { 
                sorter: false 
            }
        } 
    }); 
</script>