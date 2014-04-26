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
        createItemBuyConfirm(form);
    });
});

function createItemBuyConfirm(form) {
    var item_id = form.find('input[name="items"]').attr('value');
    bootbox.dialog({
        message: createItemLayout(item_id),
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

function createItemLayout(item_id) {
    var item_div = $('[data-item-id=' + item_id +']');
    var item_name = item_div.find('span.item-name').text();
    var item_price = item_div.find('span.item-price').text();
    var item_image = item_div.find('img').attr('src');
    var html = '<div class="row">' +
        '<div class="col-lg-6"><img src="' + item_image + '" class=" img-thumbnail img-responsive img-confirm-small"></div>' +
        '<div class="col-lg-6"><div class="row">Name: <strong><span class="text-danger">' + item_name + '</span></strong></div>' +
        '<div class="row">Price: <strong><span class="text-danger">' + item_price + '</span></strong></div></div>' +
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
    this.form.attr('value', this.element.attr('data-item-id'));
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
