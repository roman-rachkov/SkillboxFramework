'use strict';

const toggleHidden = (...fields) => {

    fields.forEach((field) => {

        if (field.hidden === true) {

            field.hidden = false;

        } else {

            field.hidden = true;

        }
    });
};

const labelHidden = (form) => {

    form.addEventListener('focusout', (evt) => {

        const field = evt.target;
        const label = field.nextElementSibling;

        if (field.tagName === 'INPUT' && field.value && label) {

            label.hidden = true;

        } else if (label) {

            label.hidden = false;

        }
    });
};

const toggleDelivery = (elem) => {

    const delivery = elem.querySelector('.js-radio');
    const deliveryYes = elem.querySelector('.shop-page__delivery--yes');
    const deliveryNo = elem.querySelector('.shop-page__delivery--no');
    const fields = deliveryYes.querySelectorAll('.custom-form__input');

    delivery.addEventListener('change', (evt) => {

        if (evt.target.id === 'dev-no') {

            fields.forEach(inp => {
                if (inp.required === true) {
                    inp.required = false;
                }
            });


            toggleHidden(deliveryYes, deliveryNo);

            deliveryNo.classList.add('fade');
            setTimeout(() => {
                deliveryNo.classList.remove('fade');
            }, 1000);

        } else {

            fields.forEach(inp => {
                if (inp.required === false) {
                    inp.required = true;
                }
            });

            toggleHidden(deliveryYes, deliveryNo);

            deliveryYes.classList.add('fade');
            setTimeout(() => {
                deliveryYes.classList.remove('fade');
            }, 1000);
        }
    });
};

const filterWrapper = document.querySelector('.filter__list');
if (filterWrapper) {

    filterWrapper.addEventListener('click', evt => {

        const filterList = filterWrapper.querySelectorAll('.filter__list-item');

        filterList.forEach(filter => {

            if (filter.classList.contains('active')) {

                filter.classList.remove('active');

            }

        });

        const filter = evt.target;

        filter.classList.add('active');

    });

}

const shopList = document.querySelector('.shop__list');
if (shopList) {

    shopList.addEventListener('click', (evt) => {

        const prod = evt.path || (evt.composedPath && evt.composedPath());
        ;

        if (prod.some(pathItem => pathItem.classList && pathItem.classList.contains('shop__item'))) {

            const shopOrder = document.querySelector('.shop-page__order');

            toggleHidden(document.querySelector('.intro'), document.querySelector('.shop'), shopOrder);

            window.scroll(0, 0);

            shopOrder.classList.add('fade');
            setTimeout(() => shopOrder.classList.remove('fade'), 1000);

            const form = shopOrder.querySelector('.custom-form');
            labelHidden(form);

            toggleDelivery(shopOrder);

            const buttonOrder = shopOrder.querySelector('.button');
            const popupEnd = document.querySelector('.shop-page__popup-end');

            buttonOrder.addEventListener('click', (evt) => {

                form.noValidate = true;

                const inputs = Array.from(shopOrder.querySelectorAll('[required]'));

                inputs.forEach(inp => {

                    if (!!inp.value) {

                        if (inp.classList.contains('custom-form__input--error')) {
                            inp.classList.remove('custom-form__input--error');
                        }

                    } else {

                        inp.classList.add('custom-form__input--error');

                    }
                });

                if (inputs.every(inp => !!inp.value)) {

                    evt.preventDefault();


                    let send = true;
                    let data = new FormData();
                    $('.shop-page__order form input:visible, .shop-page__order form input[name=id], .shop-page__order form input[type=radio]:checked').each(function (idx, el) {
                        if ($(this).is('[required]') && $(this).val().length == 0) {
                            send = false;
                        }
                        data.append($(this).attr('name'), $(this).val());
                    });
                    if (send) {

                        $.ajax({
                            url: '/order',//$(form).attr('action'),
                            type: 'POST',
                            data: data,
                            cache: false,
                            dataType: 'json',
                            processData: false,
                            contentType: false,
                            headers:{
                              'X-requested-with' : 'XMLHttpRequest',
                            },
                            success: function (data) {
                                if (data.success) {
                                    toggleHidden(shopOrder, popupEnd);

                                    popupEnd.classList.add('fade');
                                    setTimeout(() => popupEnd.classList.remove('fade'), 1000);

                                    window.scroll(0, 0);

                                    const buttonEnd = popupEnd.querySelector('.button');

                                    buttonEnd.addEventListener('click', () => {

                                        popupEnd.classList.add('fade-reverse');

                                        setTimeout(() => {

                                            popupEnd.classList.remove('fade-reverse');

                                            toggleHidden(popupEnd, document.querySelector('.intro'), document.querySelector('.shop'));

                                        }, 1000);

                                    });
                                } else {
                                    showError();
                                }
                            },
                            error: function (data) {
                                showError();
                            },
                            complete: function (data) {
                                console.log(data);
                            }
                        });
                    }
                } else {
                    window.scroll(0, 0);
                    evt.preventDefault();
                }
            });
        }
    });
}

const pageOrderList = document.querySelector('.page-order__list');
if (pageOrderList) {

    pageOrderList.addEventListener('click', evt => {


        if (evt.target.classList && evt.target.classList.contains('order-item__toggle')) {
            var path = evt.path || (evt.composedPath && evt.composedPath());
            Array.from(path).forEach(element => {

                if (element.classList && element.classList.contains('page-order__item')) {

                    element.classList.toggle('order-item--active');

                }

            });

            evt.target.classList.toggle('order-item__toggle--active');

        }

        if (evt.target.classList && evt.target.classList.contains('order-item__btn')) {

            var data = new FormData();
            data.append('id', $(evt.target).data('id'));
            $.ajax({
                url: '/admin/status',
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                headers:{
                    'X-requested-with' : 'XMLHttpRequest',
                },
                success: function (data) {
                    if (data.success) {
                        const status = evt.target.previousElementSibling;

                        if (status.classList && status.classList.contains('order-item__info--no')) {
                            status.textContent = 'Выполнено';
                        } else {
                            status.textContent = 'Не выполнено';
                        }

                        status.classList.toggle('order-item__info--no');
                        status.classList.toggle('order-item__info--yes');
                        showSuccess()
                    } else {
                        showError();
                    }
                },
                error: function (data) {
                    showError();
                },
                complete: function (data) {
                    console.log(data);
                }

            });
        }
    });

}

const checkList = (list, btn) => {

    if (list.children.length === 1) {

        btn.hidden = false;

    } else {
        btn.hidden = true;
    }

};
const addList = document.querySelector('.add-list');
if (addList) {

    const form = document.querySelector('.custom-form');
    labelHidden(form);

    const addButton = addList.querySelector('.add-list__item--add');
    const addInput = addList.querySelector('#product-photo');

    checkList(addList, addButton);

    addInput.addEventListener('change', evt => {

        const template = document.createElement('LI');
        const img = document.createElement('IMG');

        template.className = 'add-list__item add-list__item--active';
        template.addEventListener('click', evt => {
            addList.removeChild(evt.target);
            addInput.value = '';
            checkList(addList, addButton);
        });

        const file = evt.target.files[0];
        const reader = new FileReader();

        reader.onload = (evt) => {
            img.src = evt.target.result;
            template.appendChild(img);
            addList.appendChild(template);
            checkList(addList, addButton);
        };

        reader.readAsDataURL(file);

    });

    const button = document.querySelector('.button');
    const popupEnd = document.querySelector('.page-add__popup-end');

    button.addEventListener('click', (evt) => {

        evt.preventDefault();

        let data = new FormData();
        var fields = $(form).find('input[name], select[name], textarea[name]');
        fields.each(function () {
            data.append($(this).attr('name'), $(this).is('[type=checkbox') ? $(this).is(':checked') : $(this).val());
        });

        try {
            $.each($(form).find('input[type=file]')[0].files, function (i, v) {
                data.append('file[' + i + ']', v);
            });
        }
        catch (e) {
            console.log(e);
        }


        $.ajax({
            url: '/admin/goods/add',//$(form).attr('action'),
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false,
            headers:{
                'X-requested-with' : 'XMLHttpRequest',
            },
            success: function (data) {
                console.log(data);
                if(data.success){
                    form.hidden = true;
                    popupEnd.hidden = false;
                } else {
                    showError();
                }
            },
            error: function (data) {
                console.log(data);
            },
            complete: function (data) {
                console.log(data);
            }
        });


        //form.hidden = true;
        // popupEnd.hidden = false;

    })

}

const productsList = document.querySelector('.page-products__list');
if (productsList) {

    productsList.addEventListener('click', evt => {

        const target = evt.target;

        if (target.classList && target.classList.contains('product-item__delete')) {

            productsList.removeChild(target.parentElement);

        }

    });

}

// jquery range maxmin
if (document.querySelector('.shop-page')) {

    $('.range__line').slider({
        min: 350,
        max: 32000,
        values: [$('.range__res-item.min-price').data('min'), $('.range__res-item.max-price').data('max')],
        range: true,
        stop: function (event, ui) {

            $('.min-price').text($('.range__line').slider('values', 0) + ' руб.');
            $('.max-price').text($('.range__line').slider('values', 1) + ' руб.');

        },
        slide: function (event, ui) {

            $('.min-price').text($('.range__line').slider('values', 0) + ' руб.');
            $('.max-price').text($('.range__line').slider('values', 1) + ' руб.');

        }
    });

}

/*-----------------------------------------------------------------------------*/

$(document).ready(function () {

    if ($('.main-menu__item.active').length == 0 && location.href.indexOf('admin') == -1) {
        $('.main-menu__item[href="/"]').addClass('active');
    }

    $('.shop__filter a').click(function (e) {
        e.preventDefault();
        setTimeout(updatePage, 1); //Без этого костыля не успевает прочитать что в категориях Оо
    });

    $('.shop__filter form').submit(function (e) {
        e.preventDefault();
        updatePage();
        return false;
    });

    $('.shop__wrapper .shop__sorting select').change(function (e) {
        updatePage();
    })


    $('article.shop__item.product').click(function (e) {
        $('.shop-page__order form input[name=id]').val($(this).data('id'));
    });

    $('input[name=delivery]').change(function (e) {
        let form = $(this).closest('form');

        let delivery = form.find('.delivery-cost');
        delivery.toggleClass('show');

        if(delivery.is(':visible')){
            let str = '.shop.container .shop__item.product[data-id='+form.children('input[name=id]').val()+'] .product__price';
            let price = parseInt($(str).text().replace(/\s+/g,''));
            let minCost = parseInt(delivery.data('minprice'));
            if(price < minCost){
                str = 'Стоимость товара меньше чем '+minCost+' руб. Стоимость доставки '+delivery.data('deliverycost')+' руб.';
                delivery.text(str);
            } else {
                str = 'Стоимость товара больше чем '+minCost+' руб. Стоимость доставки 0 руб.';
                delivery.text(str);
            }

        }

    });

});

function getFilters() {
    return {
        cat: $('.shop__filter.filter .filter__wrapper.categories a.filter__list-item.active').data('cat'),
        minPrice: parseInt($('.shop__filter.filter .filter__wrapper.price-range .filter__range.range .range__res .range__res-item.min-price').text()),
        maxPrice: parseInt($('.shop__filter.filter .filter__wrapper.price-range .filter__range.range .range__res .range__res-item.max-price').text()),
        new: $('.shop__filter.filter fieldset input#new').is(':checked'),
        sale: $('.shop__filter.filter fieldset input#sale').is(':checked'),
        sort: $('.shop__wrapper .shop__sorting select[name=sort]').val(),
        order: $('.shop__wrapper .shop__sorting select[name=order]').val(),
    };
}

function updatePage() {
    var filters = getFilters();
    var str = "?"
    for (var key in filters) {
        str += key + "=" + filters[key] + "&";
    }
    str = str.substr(0, str.length - 1);
    location.href = '/' + str;
}

function showError() {
    let error = $('<div>', {}).css({
        position: 'fixed',
        background: 'white',
        top: 50,
        left: '50%',
        transform: 'translateX(-50%)',
        border: '1px solid red',
        padding: '5px 10px',
        display: 'none'

    }).text('Что-то пошло не так. Пожалуйста обновите страницу и попробуйте снова').appendTo('body').show('fast');
    setTimeout(() => {
        error.hide('slow');
    }, 3000)
}

function showSuccess() {
    let error = $('<div>', {}).css({
        position: 'fixed',
        background: 'white',
        top: 50,
        left: '50%',
        transform: 'translateX(-50%)',
        border: '1px solid green',
        padding: '5px 10px',
        display: 'none'

    }).text('Данные успешно обновлены.').appendTo('body').show('fast');
    setTimeout(() => {
        error.hide('slow');
    }, 3000)
}