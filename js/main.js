jQuery(function($) {
    $('.list').on('click', '.actions', function() {
        if ($(this).hasClass('add-row')) {
            $(this).closest('.flex-center').after(template);
        } else {
            var list = $('.flex-center');
            if (list.length > 1) {
                $(this).closest('.flex-center').remove();
            }

        }
    });

    $('#mobile-redirects').on('submit', function(e) {
        e.preventDefault();
        showLoader();
        $.post({
            url: ajaxurl,
            data: $('#mobile-redirects').serialize()
        }).done(function(resp) {
            resp = JSON.parse(resp);
            if (resp.success) {
                alertify.notify('Success', 'success', 2);
                $('#remove-all').closest('.col-xs-12').removeClass('hidden');
            } else {
                alertify.notify(resp.data, 'error', 2);
            }
            removeLoader();
        });
    });

    var template = '<div class="col-sm-8 flex-center">\
		<div class="form-group col-xs-11">\
			<div class="row">\
				<div class="col-xs-12 col-sm-6">\
					<label>Request Url</label>\
					<input type="url" class="form-control" name="request_url[]" placeholder="http://source.com" required>\
				</div>\
				<div class="col-xs-12 col-sm-6">\
					<label>Redirect Url</label>\
					<input type="url" class="form-control" name="redirect_url[]" placeholder="http://destination.com" required>\
				</div>\
			</div>\
		</div>\
		<div class="col-xs-1">\
			<a href="javascipt:void(0)" class="actions remove-row" title="Delete Row"><i class="fa fa-2x fa-minus-circle" aria-hidden="true"></i></a>\
			<a href="javascipt:void(0)" class="actions add-row" title="Add Row"><i class="fa fa-2x fa-plus-circle" aria-hidden="true"></i></a>\
		</div>\
	</div>';

    $('#remove-all').on('click', function() {
        alertify.confirm('Are you sure?', 'You are going to clear All redirects',
            function() {
                showLoader();
                $.post({
                    url: ajaxurl,
                    data: {
                        "action": "multi_mr_clear_redirects"
                    }
                }).done(function(data) {
                    console.log(data);
                    var resp = JSON.parse(data);
                    if (!resp.success) {
                        alertify.alert('Wops', resp.data);
                    } else {
                        $('.flex-center').remove();
                        console.log('template');
                        $('.list').append(template);
                        $('#remove-all').closest('.col-xs-12').addClass('hidden');
                        alertify.notify(resp.data, 'success', 2);
                    }
                    removeLoader();
                });
            },
            function() {
                alertify.error('Cancel')
            }
        );
    })
})

function showLoader() {
    jQuery('body').append('<div class="loader"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></div>');
}

function removeLoader() {
    jQuery('.loader').remove();
}