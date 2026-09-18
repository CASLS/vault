<?php
use kartik\file\FileInputAsset;

FileInputAsset::register($this);

?>
<input id="mediaInput" name="mediaFile" type="file" multiple>
<br/>
<div class="clearfix"></div>
<script type="text/javascript">
$(document).ready(function(){
	var $el1 = $("#mediaInput");
	$el1.fileinput({
	    uploadUrl: "/media/media-upload-ajax",
	    uploadAsync: false,
	    showUpload: false, // hide upload button
	    showRemove: false, // hide remove button
	    minFileCount: 1,
	    maxFileCount: 5,
	    initialPreviewAsData: true
	}).on("filebatchselected", function(event, files) {
	    $el1.fileinput("upload");
	}).on('filebatchuploadsuccess', function(event, data) {
	    var form = data.form, files = data.files, extra = data.extra,
        response = data.response, reader = data.reader;
	    console.log('File batch upload success');
	    location.reload();
	});
});
</script>