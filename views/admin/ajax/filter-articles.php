<?php if($includeForm): ?>
<form>
<?php endif; ?>
<div id="filter-options" class="text-center row hidden" style="background-color: #f2f2f2; margin-bottom: 20px; padding: 20px;">
    <input type="hidden" value="article"/>
    
    <div class="row">
        <div class="four columns text-left">
            You can search for articles using a generic search or using special attributes.
        </div>
        <div class="seven columns text-left">
            <li class="append field">
                                    
                <input id="filter-search" class="xwide search input" type="text" data-option="disable-return" data-submit="#submit-search" placeholder="Search for an article" />
                <span class="adjoined">
                    <?php if($inlineSearch): ?>
                        <a id="submit-search" data-action="filter" data-list="#article-list" data-search="#filter-search" data-type="article"><i class="fa fa-search"></i></a>
                    <?php else: ?>
                        <a id="submit-search" data-action="filter" data-list="#article-list" data-search="#filter-search" data-type="article"><i class="fa fa-search"></i></a>
                    <?php endif;?>
                </span>

            </li>
        </div>  
        <div class="one columns text-right"><i class="fa fa-times" style="padding:10px" data-action="toggle_element" data-toggle="#filter-options"></i></div>
    </div>         
    
    <BR/><BR/>
    <div class="row text-left">
        <div class="twelve columns">
            <b>How to search for articles</b><BR/>
            <div>
                Using the attributes below you can filter articles. All attributes must be separated by a comma. 
                <BR/><h4>Example search: <code>issue: April 2014, title: Editor Letter</code></h4>
            </div>
            
            <div class="row text-left" data-action="toggle_element" data-button-toggle=".legend-button" data-toggle="#legend">
                <i class="fa fa-question-circle"></i> Need help searching? Show Legend
            </div>
            
            <BR/>
            
            <div id="legend" class="key hidden" style="background-color: #DADADA; padding: 10px;">              
                <b>Article Metadata</b><br/>
                <div>
                    Articles can be filtered using the following metadata fields: <BR/>
                    <code>issue, title, name, author, description, kicker, tags, userData, content (article's content)</code>
                    <BR/><BR/>
                    Example search: <code>issue: April 2014, title: Editor Letter</code>
                </div>
                
                <BR/>
                <b>Date Range</b><br/>
                <div>
                    Articles can be filtered using a start date <code>dateStart</code> end date <code>dateEnd</code> or range (supplying both dateStart and dateEnd). Dates must be formatted as MM/DD/YYYY
                    <BR/><BR/>
                    Example search: <code>dateStart: 02/04/2013, dateEnd: 02/20/2013</code>
                </div>
                
                <BR/>
                <b>Custom Field</b><br/>
                <div>
                    Articles can be filtered using a custom field. You must specify two values: <code>customMeta</code> and <code>customMeta</code>
                    <BR/><BR/>
                    Example search: <code>customMeta: issueType, customValue: cartoon</code>
                </div>
            </div>
        </div>
    </div>
    
     <div class="twelve columns hidden">
        <p>For example, here's a search for a custom field for only posts:<br/><code>postType:post, customMeta:issue, customValue:July 2013</code></p>
    </div>
</div>

<?php if($includeForm): ?>
</form>
<?php endif; ?>