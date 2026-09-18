var globalEditorConfig = {
		toolbarGroups : [
			{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
			{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
			{ name: 'links', groups: [ 'links' ] },
			{ name: 'insert', groups: [ 'insert' ] },
			{ name: 'forms', groups: [ 'forms' ] },
			{ name: 'tools', groups: [ 'tools' ] },
			{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
			{ name: 'others', groups: [ 'others' ] },
			{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
			{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
			{ name: 'styles', groups: [ 'styles' ] },
			{ name: 'colors', groups: [ 'colors' ] },
			{ name: 'about', groups: [ 'about' ] }
		],

		removeButtons : 'Smiley,Subscript,Superscript,Image,Table,HorizontalRule,PageBreak,Iframe,Form,Flash,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,ShowBlocks,Maximize,Save,NewPage,Preview,Print,Templates,Scayt,CreateDiv,Blockquote,Styles,Format,Font,About'
	};

function updateCkEditorContent(editor){
	editor.updateElement();
}

$(function () {
	/* Remove Media button on click handlers for removing media from AR Targets */
    $(".removeARMediaBtn").click(function (e) { 
        var hiddenInput = $(this).parent().parent().prev().find("#artarget-media_id");
        hiddenInput.val(""); //Empty the value. 
        var mediaWrapper = $(this).parent();
		mediaWrapper.slideUp(500, function(){
            mediaWrapper.remove();
		});
    });
    $(".removeOverlayMediaBtn").click(function (e) { 
        var hiddenInput = $(this).parent().parent().prev().find("#artarget-overlay_media_id");
		hiddenInput.val(""); //Empty the value. 
		var mediaWrapper = $(this).parent();
		mediaWrapper.slideUp(500, function(){
            mediaWrapper.remove();
		});
	});
	$(".removeAudioMediaBtn").click(function (e) { 
        var hiddenInput = $(this).parent().parent().prev().find("#artarget-audio_media_id");
		hiddenInput.val(""); //Empty the value. 
		var mediaWrapper = $(this).parent();
		mediaWrapper.slideUp(500, function(){
            mediaWrapper.remove();
		});
    });
});