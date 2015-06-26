jQuery(document).ready(function($) {

// Variable declared to store the Data Object of the wordPress media upload window

  var file_frame;

  jQuery('.aria_upload_button').live('click', function( event ){
    
    event.preventDefault();

    // If the media frame already exists, reopen it.

    if ( file_frame ) {

      file_frame.open();

      return;

    }

    // Create the media frame.

    file_frame = wp.media.frames.file_frame = wp.media({

      multiple: false

    });

    // When an image is selected, run a callback.

    file_frame.on( 'select', function() {

      // Now we collect the returned JSON data from the uploaded file and set set it as a thumbnail image on the page

      attachment = file_frame.state().get('selection').first().toJSON();

      // Now we do something with the collected array 'attachment'

      $("#addfilecontainer").attr("value", attachment.id);

      $("#pickfilebutton").after("<p class='filenameDisplay'>" + attachment.filename + "</p>");

    });

    // Finally, open the modal

    file_frame.open();

  });


 
});