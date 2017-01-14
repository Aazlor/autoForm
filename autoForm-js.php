<script>
$(document).ready(function() {

	$('.Required:not(:checkbox)').on('blur', function() {
		if($(this).hasClass('Select')){
			var getInputName = $(this).data('name');
			var value = $.trim($('input[name="'+getInputName+'"]').val());
		}
		else
			var value = $.trim($(this).val());

		if(value != ''){
			$(this).closest('.Field').removeClass('Error');
			if($(this).hasClass('Email')){
				if(validateEmail($(this).val()) === false){
					$(this).addClass('Error');
					$(this).closest('.Field').addClass('Error');
				}
				else{
					$(this).removeClass('Error');
					$(this).closest('.Field').removeClass('Error');				
				}
			}
		}
		else
			$(this).closest('.Field').addClass('Error');
	});


	$('.Dynamic').closest('.Field').each(function(){
		var dynamic_field_name = $(this).find('label').text();
		$(this).find('label').remove();

		$(this).find('.Dynamic').attr('name', $(this).find('.Dynamic').attr('name')+'[]');

		$(this).find('.Dynamic').wrap('<div class="Field Dynamic"></div>');
		var addLabel = '<label>'+dynamic_field_name+'</label>';
		$(this).find('div.Dynamic').prepend(addLabel);
		$(this).find('div.Dynamic').last().after('<div class="Add">Add '+dynamic_field_name+'</div><div class="Clear" id="Break"></div>');

		if($(this).find('div.Dynamic').length > 1){
			var cancel = '<span class="Cancel">X</span>';
			$(cancel).appendTo($(this).find('div.Dynamic'));				
		}
	});


	$('.Fields').on('click', '.Add', function() {
		var getRightField = $(this).closest('.Field').find('div.Dynamic');
		if($(getRightField).length < 2){
			var cancel = '<span class="Cancel">X</span>';
			$(cancel).appendTo(getRightField);
		}
		var newDiv = $(getRightField).first().clone().css('display', 'none');
		$(newDiv).find('input').val('');
		$(this).before(newDiv);
		$(newDiv).slideDown('fast');
	});

	$('.Fields').on('click', '.Cancel', function() {
		$(this).closest('div.Dynamic').slideUp('fast', function(){
			$(this).remove();
		});

		if($(this).closest('.Field').find('div.Dynamic').length < 3){
			$(this).closest('.Field').find('div.Dynamic').find('span').removeClass('Cancel').fadeOut('fast', function(){
				$(this).remove();
			});
		}
	});


	$('.Submit .Inner').append('<input type="hidden" name="submit_button" value="yes"><input type="submit" name="submit_button" value="<?= $FormSubmitText ?>">');
	$('.Fields').wrapAll('<form name="form" id="form" class="form" action="over-the-top-anti-spam" method="post" novalidate="novalidate"></form>');


	$('.Submit').on('click', 'input, a', function(e){
		e.preventDefault();
	});

	$('.Submit').on('click', 'a, input[type="submit"]', function(e){

		$('#form').attr('action', '');
		$('.Error').removeClass('Error');

		$('.errorSpan').remove();


		if(($('#Optin_Yes').is(':checked')) || $('#Modal').length < 1){
			if(validateForm() === true){
				$(this).off();
				$('#form').submit();
			}
			else{
				invalid = false;
				e.preventDefault();
				e.stopPropagation();
			}
		}
		else{
			$('#Modal').dialog({
				modal: true,
				closeOnEscape: false,
				open: function(event, ui) {
					$(".ui-draggable-handle").remove();
				},
				width: 'auto',
				height: 'auto',
			});
			$('.Wrap').addClass('blur');
			e.preventDefault();
			e.stopPropagation();
		}


		$('html,body').animate({scrollTop: $('.Error').first().offset.top}, 'slow');
		e.preventDefault();
		e.stopImmediatePropagation();
		e.stopPropagation();
	});

	$('.Button.No').click(function(){
		$('#Modal').dialog( "close" ).remove();
		$('.Wrap').removeClass('blur');
		$('.Submit div').trigger('click');
	});

	$('.Button.Yes').click(function(){
		$('#Optin_Yes').trigger('click');
		$('#Modal').dialog( "close" );
		$('.Wrap').removeClass('blur');
		if($('#Email').val() != '')
			$('.Submit div').trigger('click');
		else
			$('#Email').focus();
	});

	$('body').on('click', '.datetime', function(){
		$(this).datetimepicker({
			format:'<?= $datetime_format ?>',
		});
	});

	$('body').on('click', '.date', function(){
		$(this).datepicker({
			format:'<?= $datetime_format ?>',
		});
	});

	$('.datetime').datetimepicker({
		format:'<?= $datetime_format ?>',
	});

	$('.date').datepicker({
		format:'<?= $datetime_format ?>',
	});

	$('body').on('click', '.Select span', function(e){
		$(this).closest('div.Select').find('span').text($(this).data('value')).css('color', '#414042');		
		$(this).closest('.Select').find('.slimScrollDiv, ul').slideDown('slow');
		$(this).closest('.Select').find('span').css('background-image', 'url(./site/select-up.png)');
		$(this).closest('.Select').find('.slimScrollDiv, ul').addClass('open');
		e.stopPropagation();
	});

	$('body').on('click', '.Select ul li', function(e){
		$(this).closest('div.Select').find('span').text($(this).text()).css('color', '#000');
		$(this).closest('.Select').find('.slimScrollDiv, ul').slideUp('slow');
		$(this).closest('.Select').find('span').css('background-image', 'url(./site/select-down.png)');
		$(this).closest('.Field').removeClass('Error');

		var name = $(this).closest('.Select').data('name');
		$('input[name="'+name+'"]').attr('value', $(this).data('value'));

		// console.log($(this).data('value'));

		if($(this).closest('div.Select').data('name') == 'To_Shift_or_Not_to_Shift?'){
			var div = $(this).closest('div.Select').find('span').wrapInner('<div class="smaller"></div>');
		}
		e.stopPropagation();
	});

	$('body').on('click', '.Form', function(){
		$('.open').closest('div.Select').find('span').text(
			$('input[name="'+$('.open').closest('.Select').find('span').data('value')+'"]').val()
		).css('color', '#414042');		

		$('.open').slideUp('fast').removeClass('open');
	});

	$('.Submit, .Button').mouseup(function() {
		$(this).find('.ShadowOver').css('background-color', 'rgba(0,0,0,.15)');
		$(this).find('.ShadowUnder').css('background-color', 'rgba(0,0,0,.15)');
	}).mousedown(function(e) {
		$(this).find('.ShadowOver').css('background-color', 'rgba(0,0,0,.3)');
		$(this).find('.ShadowUnder').css('background-color', 'rgba(0,0,0,.0)');
	});

	function validateForm(){
		var error = 0;
		var current = '';

		$('#form').find('.Required[name$="[]"]').each(function(){
			var name = $(this).attr('name');
			if(current != name){
				current = name;
				if($(this).attr('type') == 'checkbox' && $('.Required[name="'+name+'"]:checked').length < 1){
					$('.Required[name="'+name+'"]').addClass('Error');
					var error_msg = '<?= $Form_Error_Messages[Field_Generic] ?>';
					$(this).closest('.Field').append(error_msg);
					$(this).closest('.Field').addClass('Error');
					error = 1;
				}
				else if($(this).attr('type') == 'checkbox' && $('.Required[name="'+name+'"]:checked').length > 0){
					$('.Required[name="'+name+'"]:checked').removeClass('Error');
					var error_msg = '<?= $Form_Error_Messages[Field_Generic] ?>';
					$(this).closest('.Field').removeClass('Error');
				}
			}
		});

		$('#form').find('.Required:not([name*="[]"])').each(function(){
			if($(this).hasClass('Select') == true){
				if($(this).find('input').val() == ''){
					$(this).closest('span').addClass('Error');
					$(this).closest('.Field').addClass('Error');
					var error_msg = '<?= $Form_Error_Messages[Field_Generic] ?>';
					$(this).after(error_msg);
					error = 1;
				}
				else{
					$(this).closest('span').removeClass('Error');
					$(this).closest('.Field').removeClass('Error');
				}
			}
			else if($(this).val() == ''){
				$(this).addClass('Error');
				$(this).closest('.Field').addClass('Error');
				var error_msg = '<?= $Form_Error_Messages[Field_Generic] ?>';
				$(this).after(error_msg);
				error = 1;
			}
			else{
				$(this).closest('span').removeClass('Error');
				$(this).closest('.Field').removeClass('Error');
			}
		});

		$('#form').find('input.Email').each(function(){
			if($(this).hasClass('Required') || $(this).val() != '' || $('#Optin_Yes').is(':checked') === true){
				if(validateEmail($(this).val()) === false){
					$(this).addClass('Error');
					$(this).closest('.Field').addClass('Error');
					$('#Optin_Yes').closest('.Field').addClass('Error');
					var error_msg = '<?= $Form_Error_Messages[Invalid_Email] ?>';
					$(this).after(error_msg);
					error = 1;
				}
				else if($('#Optin_Yes').is(':checked') === false){
					$(this).addClass('Error');
					$(this).closest('.Field').addClass('Error');
					$('#Optin_Yes').closest('.Field').addClass('Error');
					var error_msg = '<?= $Form_Error_Messages[Invalid_Email] ?>';
					$(this).after(error_msg);
					error = 1;
				}
				else{
					$(this).closest('span').removeClass('Error');
					$(this).closest('.Field').removeClass('Error');
				}
			}
		});

		$('#form').find('input.Phone').each(function(){
			if($(this).hasClass('Required') || $(this).val() != ''){
				if(validatePhone($(this).val()) === false){
					$(this).addClass('Error');
					$(this).closest('.Field').addClass('Error');
					var error_msg = '<?= $Form_Error_Messages[Invalid_Phone] ?>';
					$(this).after(error_msg);
					error = 1;
				}
				else{
					$(this).closest('span').removeClass('Error');
					$(this).closest('.Field').removeClass('Error');
				}
			}
		});

		$('#form').find('input#Zip').each(function(){
			if($(this).hasClass('Required') || $(this).val() != ''){
				console.log();
				if(validateZip($(this).val()) === false){
					$(this).addClass('Error');
					$(this).closest('.Field').addClass('Error');
					var error_msg = '<?= $Form_Error_Messages[Invalid_Phone] ?>';
					$(this).after(error_msg);
					error = 1;
				}
				else{
					$(this).closest('span').removeClass('Error');
					$(this).closest('.Field').removeClass('Error');
				}
			}
		});

		if(error == 0){
			return true;
		}
		else
			return false;
	}


	function validateEmail(email) {

		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		// var superEmailReg = [a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?;
		//var emailReg = /^.*?@.*?\..*$/;
		return regex.test( email );
	}

	function validatePhone(phone) {
		number = phone.replace(/\D/g,'');
		if( number.length == 10 ) {
			return true;
		} else {
			return false;
		}
	}

	function validateZip(zip) {
		zip = zip.replace(/\D/g,'');
		if( zip.length == 5 ) {
			return true;
		} else {
			return false;
		}
	}

	function requiredCheck() {
		var status = true;
		if($('input:checkbox:checked').length > 0)
			$('.Required:checkbox').parent().parent().find('.errorSpan').fadeOut('fast');
		else {
			if($(window).width() >= 400)
				$('.Required:checkbox').parent().parent().find('.errorSpan').fadeIn('fast');
			else
				$('.Required:checkbox').parent().parent().find('.errorSpan').fadeIn('fast');
			status = false;
		}
		$('.Required:not(:checkbox)').each(function() {
			if(!$(this).val()){
				$(this).parent().find('.errorSpan').fadeIn('fast');
				status = false;
			}
			else{
				$(this).parent().find('.errorSpan').fadeOut('fast');
			}
		});
		$('.Email').each(function() {
			if(!validateEmail($(this).val())){
				$(this).siblings('span:contains(Invalid)').fadeIn('fast');
				$(this).closest('.Field').addClass('Error');
				status = false;
			}
			else {
				$(this).siblings('span:contains(Invalid)').fadeOut('fast');
			}
		});
		$('input[name*="conf"]').each(function() {
			var first = $(this).siblings('input[name="'+$(this).attr('name').replace('(conf)', '')+'"]').val();
			if(first != $(this).val()) {
				$(this).parent().find('span:contains(do not match)').fadeIn('fast');
			}
			else {
				$(this).parent().find('span:contains(do not match)').fadeOut('fast');
			}
		});
		return status;
	}

	window.setTimeout(function(){
		$('.Email').blur(function(){
			if($(this).val() != ''){
				if(!validateEmail($(this).val())){
					$(this).closest('.Field').addClass('Error');
				}
			}
		});
	}, 100);
});
</script>