$(document).ready(function () {

    $.ajax({
        url: 'server.php',
        type: 'GET',
        contentType: 'application/json',
        dataType: 'json',
        data: {
        },
        success: function (response) {

            for (var item of response) {

                contact = `<div class="comment_box">
                    <span class="delete" data-id="` + item.id + `" >delete</span>
                    <span class="edit" data-id="` + item.id + `">edit</span>
                    <div class="display_name">` + item.name + `</div>
                    <div class="display_email">` + item.email + `</div>
                    <div class="display_phone_number">` + item.phone_number + `</div>
                    <div class="display_address">` + item.address + `</div>
                    </div>`;
                $('#display_area').append(contact);
            }

        }
    });


    $(document).on('click', '#submit_btn', function () {
        var name = $('#name').val();
        var email = $('#email').val();
        var phone_number = $('#phone_number').val();
        var address = $('#address').val();
        $.ajax({
            url: 'server.php',
            // type: 'POST',
            type: 'PUT',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify({
                'save': 1,
                'name': name,
                'email': email,
                'phone_number': phone_number,
                'address': address
            }),
            success: function (response) {

                saved_contact = `<div class="comment_box">
      		<span class="delete" data-id="` + response.id + `" >delete</span>
      		<span class="edit" data-id="` + response.id + `">edit</span>
      		<div class="display_name">` + response.name + `</div>
      		<div class="display_email">` + response.email + `</div>
      		<div class="display_phone_number">` + response.phone_number + `</div>
      		<div class="display_address">` + response.address + `</div>
      	  </div>`;

                $('#name').val('');
                $('#email').val('');
                $('#phone_number').val('');
                $('#address').val('');
                $('#display_area').append(saved_contact);
            }
        });
    });

    $(document).on('click', '.delete', function () {
        var id = $(this).data('id');
        $clicked_btn = $(this);
        $.ajax({
            url: 'server.php',
            type: 'GET',
            data: {
                'delete': 1,
                'id': id,
            },
            success: function (response) {

                $clicked_btn.parent().remove();
                $('#name').val('');
                $('#email').val('');
                $('#phone_number').val('');
                $('#address').val('');
            }
        });
    });
    var edit_id;
    var $edit_contact;
    $(document).on('click', '.edit', function () {
        edit_id = $(this).data('id');
        $edit_contact = $(this).parent();

        var name = $(this).siblings('.display_name').text();
        var email = $(this).siblings('.display_email').text();
        var phone_number = $(this).siblings('.display_phone_number').text();
        var address = $(this).siblings('.display_address').text();

        $('#name').val(name);
        $('#email').val(email);
        $('#phone_number').val(phone_number);
        $('#address').val(address);
        $('#submit_btn').hide();
        $('#update_btn').show();

        $('#name').attr('readonly', true);
        $('#phone_number').attr('readonly', true);
        $('#address').attr('readonly', true);
    });
    $(document).on('click', '#update_btn', function () {
        var id = edit_id;
        var name = $('#name').val();
        var email = $('#email').val();
        var phone_number = $('#phone_number').val();
        var address = $('#address').val();
        $.ajax({
            url: 'server.php',
            type: 'PUT',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify({
                'update': 1,
                'id': id,
                'name': name,
                'email': email,
                'phone_number': phone_number,
                'address': address
            }),
            success: function (response) {

                saved_contact = `<div class="comment_box">
                    <span class="delete" data-id="` + response.id + `" >delete</span>
                    <span class="edit" data-id="` + response.id + `">edit</span>
                    <div class="display_name">` + response.name + `</div>
                    <div class="display_email">` + response.email + `</div>
                    <div class="display_phone_number">` + response.phone_number + `</div>
                    <div class="display_address">` + response.address + `</div>
                    </div>`;

                $('#name').val('');
                $('#email').val('');
                $('#phone_number').val('');
                $('#address').val('');
                $('#submit_btn').show();
                $('#update_btn').hide();
                $edit_contact.replaceWith(saved_contact);

                $('#name').attr('readonly', false);
                $('#phone_number').attr('readonly', false);
                $('#address').attr('readonly', false);
            }
        });
    });
    $(document).on('click', '#search_btn', function () {
        var id = $('#id').val();

        $.ajax({
            url: 'server.php',
            type: 'GET',
            contentType: 'application/json',
            dataType: 'json',
            data: {
                'id': id
            },
            success: function (response) {

                $('#display_area').html('');
                if (response.length !== 0) {
                    returned_contact = `<div class="comment_box">
                    <span class="delete" data-id="` + response.id + `" >delete</span>
                    <span class="edit" data-id="` + response.id + `">edit</span>
                    <div class="display_name">` + response.name + `</div>
                    <div class="display_email">` + response.email + `</div>
                    <div class="display_phone_number">` + response.phone_number + `</div>
                    <div class="display_address">` + response.address + `</div>
                    </div>`;

                    $('#name').val('');
                    $('#email').val('');
                    $('#phone_number').val('');
                    $('#address').val('');
                    $('#submit_btn').show();
                    $('#update_btn').hide();
                    $('#display_area').append(returned_contact);
                }
            }
        });
    });
});