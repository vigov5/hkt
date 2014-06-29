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
        var item_user_id = form.find('input[data-form="item"]').attr('value');
        var item_object = JSON.parse(localStorage.getItem("item-user-"+item_user_id));
        item_object.amount = 1;
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
        var message = createItemLayout(item_object);
        createItemBuyConfirm(form, message);
    });

    $('.item-user-change-status').each(function (index) {
        addItemUserBtnListener(this);
    });

    $('.item-shop-change-status').each(function (index) {
        addItemShopBtnListener(this);
    });

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

    $('.shop-sell-request').click(function (e) {
        var shop_id = $(this).attr('data-shop-id');
        $.ajax({
            type: "POST",
            url: "/shop/request",
            data: {
                shop_id: shop_id
            }
        }).success(function(message) {
                var response = JSON.parse(message);
                if(response.status == 'success') {
                    bootbox.alert(response.message, function() {
                        $('.shop-sell-request').remove();
                    });
                }
            })
            .fail(function() {
                alert('Reuqest sent Fail !!!');
            });
    })

    $('#shop-buy-btn').click(function (e) {
        var item_shop_id = null;
        var has_set = false;
        var item = [];
        var item_sets = [];

        var item_shop = null;
        var sets = [];
        var amount = $('#item-shop-amount').val();
        if (isNaN(amount) || amount < 1) {
            bootbox.alert('Invalid Amount.');
            return;
        }
        $('.single-item-select').each(function (index) {
            if ($(this).hasClass('img-selected')) {
                item_shop_id = $(this).attr('data-item-shop-id');
                item.push(this);
            }
        });
        $('.multi-item-select').each(function (index) {
            has_set = true;
            if ($(this).hasClass('img-selected')) {
                item_sets.push($(this).attr('data-item-shop-id'));
            }
        });
        if (item.length != 1) {
            bootbox.alert('Invalid item. You must select one and only one item');
            return;
        } else {
            var id = $(item[0]).attr('data-item-shop-id');
            item_shop = JSON.parse(localStorage.getItem("item-shop-"+id));
        }
        if (has_set && item_sets.length == 0) {
            bootbox.alert('Invalid set. You must select at least one item from set');
            return;
        } else {
            for (i=0; i<item_sets.length; i++) {
                sets.push(JSON.parse(localStorage.getItem("item-shop-" + item_sets[i])));
            }
        }
        item_shop.amount = amount;
        var message = createShopItemLayout(item_shop, sets);
        var data = {
            item_shop_id: item_shop_id,
            amount: amount,
            item_sets: JSON.stringify(item_sets)
        }
        createItemBuyConfirm(null, message, data);
    });

    $('.btn-invoice-status').click(function() {
        var invoice_id = $(this).attr('data-invoice-id');
        var invoice_status = $(this).attr('data-invoice-status');
        $.ajax({
            type: "POST",
            url: "/invoice/changestatus",
            data: {
                invoice_id: invoice_id,
                status: invoice_status
            }
        }).success(function(message) {
                var response = JSON.parse(message);
                if(response.status == 'success') {
                    changeInvoiceData(invoice_id, response.data);
                    updateWallet(response.current_user_wallet);
                } else {
                    bootbox.alert(response.message);
                }
            })
            .fail(function() {
                alert('Reuqest sent Fail !!!');
            });
    });

    $('.btn-invoice-status-all').click(function() {
        var invoice_status = $(this).attr('data-invoice-status');
        var invoices_id = [];
        $('.btn-invoice-status' + '[data-invoice-status=' + invoice_status + ']').each(function (index) {
            invoices_id.push($(this).attr('data-invoice-id'));
        });
        if (invoices_id.length > 0) {
            $.ajax({
                type: "POST",
                url: "/invoice/changestatusall",
                data: {
                    invoices_id: invoices_id,
                    status: invoice_status
                }
            }).success(function(message) {
                var response = JSON.parse(message);
                if(response.status == 'success') {
                    var invoices = response.data;
                    for (id in invoices) {
                        changeInvoiceData(id, invoices[id]);
                    }
                    updateWallet(response.current_user_wallet);
                } else {

                }
            })
            .fail(function() {
                alert('Reuqest sent Fail !!!');
            });
        }
    });

    $('.btn-user-display-name').click(function() {
        var user_id = $(this).attr('data-user-id');
        bootbox.prompt("Input your Display Name!", function(result) {
            if (result === null || result.length == 0) {

            } else {
                $.ajax({
                    type: "POST",
                    url: "/user/changedisplayname",
                    data: {
                        user_id: user_id,
                        display_name: result
                    }
                }).success(function(message) {
                    var response = JSON.parse(message);
                    if(response.status == 'success') {
                        bootbox.alert(response.message);
                        changeDisplayName(response.display_name);
                    }
                })
                .fail(function() {
                    alert('Reuqest sent Fail !!!');
                });
            }
        });
    });

    $('.btn-change-user-place').click(function() {
        var place_name = $(this).html();
        var place = $(this).attr('data-user-place');
        var user_id = $(this).attr('data-user-id');
        if ($(this).hasClass('btn-danger')) {
            bootbox.alert('Your place is currently ' + place_name);
        } else {
            $.ajax({
                type: "POST",
                url: "/user/changeplace",
                data: {
                    user_id: user_id,
                    place: place
                }
            }).success(function(message) {
                var response = JSON.parse(message);
                if(response.status == 'success') {
                    bootbox.alert(response.message);
                    changeUserPlace(place);
                }
            })
            .fail(function() {
                alert('Reuqest sent Fail !!!');
            });
        }
        console.log(place, place_name);
    });

    //Like Button
    $('.btn-like').click(function () {
        var me = $(this);
        var target_type = me.attr('data-target-type');
        var target_id = me.attr('data-target-id');

        $.ajax({
            type: "POST",
            url: "/user/like",
            data: {
                target_type: target_type,
                target_id: target_id
            }
        }).success(function(message) {
            var response = JSON.parse(message);
                console.log(response);
            if(response.status != 'success') {
                bootbox.alert(response.message);
            } else {
                if (me.hasClass('like')) {
                    me.removeClass('like').addClass('unlike');
                } else {
                    me.removeClass('unlike').addClass('like');
                }
            }
        })
        .fail(function() {
            alert('Reuqest sent Fail !!!');
        });
    });

    // Favorite Notification
    $('.fav-noti').change(function() {
        var favorite_id = $(this).attr('data-favorite-id');
        $.ajax({
            type: "POST",
            url: "/favorite/changenotification",
            data: {
                favorite_id: favorite_id
            }
        }).success(function(message) {
            })
            .fail(function() {
                alert('Reuqest sent Fail !!!');
            });
    });

    //Isotype
    var container = $('.isotype-container');
    container.waitForImages(function() {
        if(container.length) {
            container.isotope({
                itemSelector : '.isotype-item'
            });
        }
    },null,true);

    $('#donate-coin-btn').click(function (e) {
        e.preventDefault();
        var form = $('#donate-coin-btn').closest('form');
        var target_user_id = parseInt(form.find('#target_user_id').attr('value'));
        var target_user = form.find('#target_user').val();
        var amount = parseInt(form.find('#amount').val());
        if (target_user == "" || target_user_id == 0 || isNaN(target_user_id) || amount <= 0 || isNaN(amount)) {
            bootbox.alert(' Please re-check your input');
        } else {
            var message = "Do you really want to donate " + amount + " HCoins to " + target_user + " ?";
            var data = {
                target_user_id: target_user_id,
                target_user: target_user,
                amount: amount
            }
            createDonateCoinConfirm(form, message, data);
        }
    });

    $('#transfer-money-btn').click(function (e) {
        e.preventDefault();
        var form = $('#transfer-money-btn').closest('form');
        var target_user_id = parseInt(form.find('#target_user_id').attr('value'));
        var target_user = form.find('#target_user').val();
        var amount = parseInt(form.find('#amount').val());
        var fee_bearer = parseInt(form.find('#fee_bearer').val());
        if (target_user == "" || target_user_id == 0 || isNaN(target_user_id)) {
            bootbox.alert('Recipient is invalid.');
        } else if (isNaN(fee_bearer) || !(fee_bearer == 1 || fee_bearer == 2)) {
            bootbox.alert('Transfer Bearer is invalid.');
        } else if (amount <= 0 || isNaN(amount)) {
            bootbox.alert('Amount value is invalid.');
        } else if ((amount / 1000) % 1 !== 0) {
            bootbox.alert('Amount value must be multiple of 1000.');
        } else {
            var message = "Do you really want to transfer " + amount + " VND to " + target_user + " ?";
            var data = {
                target_user_id: target_user_id,
                target_user: target_user,
                amount: amount,
                fee_bearer: fee_bearer
            }
            createMoneyTransferConfirm(message, data);
        }
    });
});

function createDonateCoinConfirm(form, message, data) {
    bootbox.dialog({
        message: message,
        title: '<strong><span class="text-primary">Are you sure ?</span></strong>',
        buttons: {
            success: {
                label: "OK",
                className: "btn-success",
                callback: function() {
                    if (form != null) {
                        form.submit();
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "/user/donate",
                            data: data
                        }).success(function(message) {
                        })
                        .fail(function() {
                            alert('Donation fail :(');
                        });
                    }
                }
            },
            danger: {
                label: "Cancel",
                className: "btn-danger"
            }
        }
    });
}

function createMoneyTransferConfirm(message, data) {
    bootbox.dialog({
        message: message,
        title: '<strong><span class="text-primary">Are you sure ?</span></strong>',
        buttons: {
            success: {
                label: "OK",
                className: "btn-success",
                callback: function() {
                    $.ajax({
                        type: "POST",
                        url: "/user/createtransfer",
                        data: data
                    }).success(function(message) {
                        var response = JSON.parse(message);
                        if(response.status) {
                            bootbox.alert(response.message, function() {
                                reload();
                            });
                        }
                    })
                    .fail(function() {
                        alert('Money transfer fail :(');
                    });
                }
            },
            danger: {
                label: "Cancel",
                className: "btn-danger"
            }
        }
    });
}

function changeUserPlace(place)
{
    $('.btn-change-user-place').each(function (index) {
        $(this).removeClass('btn-danger');
        if ($(this).attr('data-user-place') == place) {
            $(this).addClass('btn-danger');
        }
    })
}

function changeDisplayName(name)
{
    $('.user-display-name').html(name);
}

function changeInvoiceData(invoice_id, data)
{
    var tr = $('tr' + '[data-invoice-id=' + invoice_id +']');
    tr.find('.invoice-status').html(data.status_string);
    tr.find('.invoice-updated-at').html(data.updated_at);
    tr.find('.invoice-updated-by').html(data.updated_by);
    if (data.status != 0) {
        tr.find('.invoice-action').html('');
    }
}

function addItemUserBtnListener(btn)
{
    $(btn).click(function (e) {
        var item_user_id = $(this).attr('data-item-user-id');
        var status = $(this).attr('data-status');
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
                updateItemObj(item_user, 'addItemUserBtnListener', 'user');
            }
        })
        .fail(function() {
            alert('Reuqest sent Fail !!!');
        });
    })
}

function addItemShopBtnListener(btn)
{
    $(btn).click(function (e) {
        var item_shop_id = $(this).attr('data-item-shop-id');
        var status = $(this).attr('data-status');
        $.ajax({
            type: "POST",
            url: "/itemshop/changestatus",
            data: {
                item_shop_id: item_shop_id,
                status: status
            }
        }).success(function(message) {
            var response = JSON.parse(message);
            if(response.status == 'success') {
                var item_shop = response.data;
                updateItemObj(item_shop, 'addItemShopBtnListener', 'shop');
            }
        })
        .fail(function() {
            alert('Reuqest sent Fail !!!');
        });
    })
}

function updateItemObj(item_obj, callback, type)
{
    var class_name = '';
    if(item_obj.is_on_sale == true) {
        class_name = 'success';
    }

    var btn_search = 'button' + '[data-item-' + type + '-id=' + item_obj.id +']';
    var btn = $(btn_search);
    var tr = btn.closest('tr');
    var html = '<td>' + item_obj.name + '</td>' +
        '<td>' + item_obj.type_value + '</td>' +
        '<td>' + item_obj.price + '</td>' +
        '<td>' + item_obj.status_value + '</td>' +
        '<td>' + item_obj.start_sale_date + '</td>' +
        '<td>' + item_obj.end_sale_date + '</td>' +
        '<td>' + item_obj.created_at + '</td>' +
        '<td>' + item_obj.updated_at + '</td>' +
        '<td>' + '<div class="btn-group-xs">' +
        '<a href="/itemuser/update/' + item_obj.id + '" class="btn btn-info btn-action" role="button">Edit</a>' +
        item_obj.btn_group +
        '</td>';
    $(tr).hide(500, function(){
        $(this).removeClass().addClass(class_name);
        $(this).html(html);
        $(this).show(500, function(){
            $(btn_search).each(function(index) {
                window[callback](this);
            });
        });
    });
}

function createItemBuyConfirm(form, message, data) {
    bootbox.dialog({
        message: message,
        title: '<strong><span class="text-primary">Do you really want to buy the following item ?</span></strong>',
        buttons: {
            success: {
                label: "OK",
                className: "btn-success",
                callback: function() {
                    if (form != null) {
                        form.submit();
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "/shop/buy",
                            data: data
                        }).success(function(message) {
                            var response = JSON.parse(message);
                            updateWallet(response.wallet);
                            bootbox.alert(response.message, function() {
                                reload();
                            });
                        })
                        .fail(function() {
                            alert('Reuqest sent Fail !!!');
                        });
                    }
                }
            },
            danger: {
                label: "Cancel",
                className: "btn-danger"
            }
        }
    });
}

function createAnnouncement(announcement)
{
    var text = '<h2 class="text-danger">HKT Announcement</h2><br />';
    text += '<strong><h4 class="text-primary">' + announcement.title + '</h4></strong><br/>' + announcement.content;
    bootbox.alert(text, function() {
        console.log("OK");
        $.ajax({
            type: "POST",
            url: "/user/readannouncement",
            data: {
                user_announcement_id: announcement.user_announcement_id
            }
        }).success(function(message) {
        })
        .fail(function() {
            alert('Reuqest sent Fail !!!');
        });
    });
}

function createItemLayout(item_object) {
    var img = item_object.img;
    if (img.indexOf('http') != 0 && img.indexOf('/') != 0) {
        img = '/' + img;
    }
    var html = '<div class="row">' +
        '<div class="col-lg-6"><img src="' + img + '" class=" img-thumbnail img-responsive img-confirm-small"></div>' +
        '<div class="col-lg-6"><div class="row">Name: <strong><span class="text-danger">' + item_object.name + '</span></strong></div>' +
        '<div class="row">Seller: <strong><span class="text-danger">' + item_object.seller + '</span></strong></div>' +
        '<div class="row">Amount: <strong><span class="text-danger">' + item_object.amount + '</span></strong></div>'+
        '<div class="row">Price: <strong><span class="text-danger">' + item_object.price  * item_object.amount + '</span></strong></div></div>' +
        '</div>';
    return html;
}

function createShopItemLayout(item_object, sets) {
    var img = item_object.img;
    if (img.indexOf('http') != 0 && img.indexOf('/') != 0) {
        img = '/' + img;
    }
    var sets_html = '';
    if (sets.length != 0) {
        sets_html = '<div class="row">Sets: <strong><span class="text-danger">';
        var count = 0;
        for (i=0; i<sets.length; i++) {
            if (count != 0) {
                sets_html += ', ';
            }
            sets_html += sets[i].name;
            count++;
        }
        sets_html += '</span></strong></div>';
    }
    var html = '<div class="row">' +
        '<div class="col-lg-6"><img src="' + img + '" class=" img-thumbnail img-responsive img-confirm-small"></div>' +
        '<div class="col-lg-6"><div class="row">Name: <strong><span class="text-danger">' + item_object.name + '</span></strong></div>' +
        '<div class="row">Seller: <strong><span class="text-danger">' + item_object.seller + '</span></strong></div>' +
        sets_html +
        '<div class="row">Amount: <strong><span class="text-danger">' + item_object.amount + '</span></strong></div>'+
        '<div class="row">Price: <strong><span class="text-danger">' + item_object.price  * item_object.amount  + '</span></strong></div></div>' +
        '</div>';
    return html;
}

function updateWallet(wallet)
{
    $('.my-wallet').html(wallet);
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

function reload()
{
    location.reload();
}
