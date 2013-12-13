<?php $fieldSlug = DPSFolioAuthor_CPT_Folio::POST_TYPE_SLUG; ?>
<div class="create-new-folio">
    <form>
        
        <input type="hidden" name="action" value="create_new_folio"/>
        <div class="field">
            <input class="wide text input" id="folioName" type="text" required name="<?php echo $fieldSlug;?>[folioName]" placeholder="Folio Name"/>
        </div>
        <br />
        <div class="field">
            <input class="wide text input" id="folioNumber" type="text" required name="<?php echo $fieldSlug;?>[folioNumber]" placeholder="Folio Number"/>
        </div>
        <br />
        <div class="field">
            <input class="wide text input" id="magazineTitle" type="text" required name="<?php echo $fieldSlug;?>[magazineTitle]" placeholder="Publication Name / Magazine Title"/>
        </div>
        <br />
        <div class="field">
            <input class="wide text input" id="folioDescription" type="text" name="<?php echo $fieldSlug;?>[folioDescription]" placeholder="Folio Description"/>
        </div>
        <br />
        <div class="field">
            <input class="wide text input datepicker" id="publicationDate" type="text" required name="<?php echo $fieldSlug;?>[publicationDate]" placeholder="Publication Date"/>
        </div>
        <br />
        <div class="field">
            <input class="wide text input datepicker" id="coverDate" type="text" name="<?php echo $fieldSlug;?>[coverDate]" placeholder="Cover Date"/>
        </div>
        <br />
        <div class="field">
            <input class="wide text input" id="filters" type="text" name="<?php echo $fieldSlug;?>[filters]" placeholder="Filters"/>
        </div>
        
        <br /><br />
        <div class="medium primary btn"><a class="" data-action="create_new_folio"><i class="icon icon-plus"></i> Create New Folio</a></div>
        <br />
        
    </form>
</div>

<script>
/*
// initialize plugin
jQuery('form').validation({
  // pass an array of required field objects
  required: [
    {
      // name should reference a form inputs name attribute
      // just passing the name property will default to a check for a present value
      <?php echo $fieldSlug;?>[folioName]: 'name',
    },
    {
      name: 'email',
      // pass a function to the validate property for complex custom validations
      // the function will receive the jQuery element itself, return true or false depending on validation
      validate: function($el) {
        return $el.val().match('@') !== null;
      }
    }
  ],
  // callback for failed validaiton on form submit
  fail: function() {
    Gumby.error('Form validation failed');
  },
  // callback for successful validation on form submit
  // if omited, form will submit normally
  submit: function(data) {
    $.ajax({
      url: 'do/something/with/data',
      data: data,
      success: function() {alert("Submitted");}
    });
  } 
});
*/
</script>