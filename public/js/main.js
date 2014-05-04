$(function() {
    $('#file-input-tab a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    $('.an-item-tab a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    $('.single-item-select').click(function() {
        img = new ImageSelector(this);
        if (img.isSelected()) {
            img.removeSelected();
        } else {
            img.removeAllSelected();
            img.setSelected();
        }
    });

    $('.multi-item-select').click(function() {
        img = new ImageSelector(this);
        img.toggle();
    })

    $('#item-buy-btn').click(function (e) {
        e.preventDefault();
        var form = $('#item-buy-btn').closest('form');
        var item_user_id = form.find('input[name="items"]').attr('value');
        var item_object = JSON.parse(localStorage.getItem("item-user-"+item_user_id));
        item_object.amount = 1;
        console.log(item_object);
        var message = createItemLayout(item_object);
        createItemBuyConfirm(form, message);
    });

    $('.buy-btn').click(function (e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var item_user_id = form.find('input[name="item_user_id"]').attr('value');
        var amount = form.find('input[name="amount"]').val();
        var item_object = JSON.parse(localStorage.getItem("item-user-"+item_user_id));
        item_object.amount = amount;
        console.log(item_object);
        var message = createItemLayout(item_object);
        createItemBuyConfirm(form, message);
    });

    $('.item-user-change-status').each(function (index) {
        addBtnListener(this);
    })

    $('.item-sell-request').click(function (e) {
        var item_id = $(this).attr('data-item-id');
        $.ajax({
            type: "POST",
            url: "/item/request",
            data: {
                item_id: item_id
            }
        }).success(function(message) {
            var response = JSON.parse(message);
            if(response.status == 'success') {
                bootbox.alert('Request has been created ! Please wait for the administrators to confirm it', function() {
                    $('#item-sell-request-' + item_id).hide();
                });
            }
        })
        .fail(function() {
            bootbox.alert('Reuqest sent Fail !!!');
        });
    });
});

function addBtnListener(btn)
{
    $(btn).click(function (e) {
        var item_user_id = $(this).attr('data-item-user-id');
        var status = $(this).attr('data-status');
        console.log(item_user_id,status);
        $.ajax({
            type: "POST",
            url: "/itemuser/changestatus",
            data: {
                item_user_id: item_user_id,
                status: status
            }
        }).success(function(message) {
            var response = JSON.parse(message);
            if(response.status == 'success') {
                var item_user = response.data;
                updateItemUser(item_user);
            }
        })
        .fail(function() {
            alert('Reuqest sent Fail !!!');
        });
    })
}
function updateItemUser(item_user)
{
    var class_name = '';
    if(item_user.is_on_sale == true) {
        class_name = 'success';
    }
    var btn = $('button' + '[data-item-user-id=' + item_user.id +']');
    var tr = btn.closest('tr');
    var html = '<td>' + item_user.name + '</td>' +
        '<td>' + item_user.price + '</td>' +
        '<td>' + item_user.status_value + '</td>' +
        '<td>' + item_user.start_sale_date + '</td>' +
        '<td>' + item_user.end_sale_date + '</td>' +
        '<td>' + item_user.created_at + '</td>' +
        '<td>' + item_user.updated_at + '</td>' +
        '<td>' + '<div class="btn-group-xs">' +
        '<a href="/itemuser/update/' + item_user.id + '" class="btn btn-info btn-action" role="button">Edit</a>' +
            item_user.btn_group +
        '</td>';
    $(tr).hide(500, function(){
        $(this).removeClass().addClass(class_name);
        $(this).html(html);
        $(this).show(500, function(){
            $('button' + '[data-item-user-id=' + item_user.id +']').each(function(index) {
                addBtnListener(this);
            });
        });
    });
}

function createItemBuyConfirm(form, message) {
    bootbox.dialog({
        message: message,
        title: '<strong><span class="text-primary">Are you really want to buy the following item ?</span></strong>',
        buttons: {
            success: {
                label: "OK",
                className: "btn-success",
                callback: function() {
                    form.submit();
                }
            },
            danger: {
                label: "Cancel",
                className: "btn-danger",
                callback: function() {
                    console.log('Cancel')
                }
            }
        }
    });
}

function createItemLayout(item_object) {
    var html = '<div class="row">' +
        '<div class="col-lg-6"><img src="/' + item_object.img + '" class=" img-thumbnail img-responsive img-confirm-small"></div>' +
        '<div class="col-lg-6"><div class="row">Name: <strong><span class="text-danger">' + item_object.name + '</span></strong></div>' +
        '<div class="row">Seller: <strong><span class="text-danger">' + item_object.seller + '</span></strong></div>' +
        '<div class="row">Amount: <strong><span class="text-danger">' + item_object.amount + '</span></strong></div>'+
        '<div class="row">Price: <strong><span class="text-danger">' + item_object.price  * item_object.amount + '</span></strong></div></div>' +
        '</div>';
    return html;
}

function ImageSelector(element) {
    this.element = $(element);
    this.data_form = this.element.attr('data-form');
    this.form = $('input' + '[data-form=' + this.data_form +']');
}

ImageSelector.prototype.isSelected = function() {
    return this.element.hasClass('img-selected');
}

ImageSelector.prototype.setSelected = function() {
    this.element.addClass('img-selected');
    this.form.attr('value', this.element.attr('data-item-user-id'));
}

ImageSelector.prototype.removeSelected = function() {
    if (this.isSelected()) {
        this.element.removeClass('img-selected');
        this.resetFormValue();
    }
}

ImageSelector.prototype.toggle = function() {
    if (this.isSelected()) {
        this.removeSelected();
    } else {
        this.setSelected();
    }
}

ImageSelector.prototype.resetFormValue = function() {
    this.form.attr('value', '');
}

ImageSelector.prototype.removeAllSelected = function() {
    this.resetFormValue();
    $('.img-selected' + '[data-form=' + this.data_form +']').removeClass('img-selected');
}
