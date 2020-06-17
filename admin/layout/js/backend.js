$(function()
{
	'use strict';



	//dashboard
	$('.toggle-info').click(function()
	{
		$(this).toggleClass('selected').parent().next(.'panel-body').fadeToggle(1000);
		if($(this).hasClass('selected'))
		{
			$(this).html('<i class="fa fa-plus fa-lg"></i>');
		}
		else
		{
			$(this).html('<i class="fa fa-minus fa-lg"></i>');
		}
	});
	
	
	
	//trigger the selectboxit
	$("select").selectBoxIt(
	{
		autoWidth: false
	});



	//hide placeholder on form focus
	$('[placeholder]').focus(function()
	{
		$(this).attr('data-text',$(this).attr('placeholder'));
		$(this).attr('placeholder','');
	}).blur(function()
	{
		$(this).attr('placeholder',$(this).attr('data-text'));
	});



	//add asterisk on required field
	$('input').each(function()
	{
		if($(this).attr('required') === 'required')
		{
			$(this).after('<span class="asterisk">*</span>');
		}
	});



	//convert password field to text field on hover
	$('.show-pass').hover(function()
	{
		$('.password').attr('type','text');
	},function()
	{
		$('.password').attr('type','password');
	});



	//confirmation message when click on delete button
	$('.confirm').click(function()
	{
		return.confirm('Are You Sure..??');
	});
	


	//category view option
	$('.cat h3').click(function()
	{
		$(this).next('.full-view').fadeToggle(250);
	});

	$('.option span').click(function()
	{
		$(this).addClass('active').siblings('span').removeClass('active');
		if($(this).data('view') === 'full')
		{
			$('.cat .full-view').fadeIn(250);
		}
		else
		{
			$('.cat .full-view').fadeOut(250);
		}
	});



	//show delete button on child cat
	$('.child-link').hover(function()
	{
		$(this).find('show-delete').fadeIn(500);
	},function()
	{
		$(this).find('show-delete').fadeOut(500);
	});



});