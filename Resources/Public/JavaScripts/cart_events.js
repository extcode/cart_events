var cart_events = (function () {

    var eventDates = document.getElementsByClassName('event-event-date');

    for (var eventDateCount=0; eventDateCount < eventDates.length; eventDateCount++) {
        var addToCartForms = eventDates[eventDateCount].querySelectorAll('form.add-to-cart-form');
        for (var addToCartFormsCount=0; addToCartFormsCount < addToCartForms.length; addToCartFormsCount++) {
            var priceCategorySelects = addToCartForms[addToCartFormsCount].querySelectorAll('.price-category-select');
            for (var priceCategorySelectCount=0; priceCategorySelectCount < priceCategorySelects.length; priceCategorySelectCount++) {

                priceCategorySelects[priceCategorySelectCount].addEventListener('change', function(){ updatePriceCategory(this, eventDates[eventDateCount]) }, false);
            }
        }
    }

    function updatePriceCategory(element, eventDate) {
        var price;

        var style = element.selectedOptions[0].style.display;
        eventDate.querySelectorAll('.event-date-price-category').forEach(el => {
            el.style.display = 'none';
        });
        eventDate.querySelector('.event-date-price-category-' + element.selectedOptions[0].value).style.display = style;

        var title = element.selectedOptions[0].getAttribute('data-title');

        if (title) {
            eventDate.querySelector('.event-date-price .regular-price').style.display = 'none';
            eventDate.querySelector('.event-date-price .special-price').style.display = 'block';

            eventDate.querySelector('.event-date-price .special-price .title').innerHTML = title;

            eventDate.querySelector('.event-date-price .regular-price .price').innerHTML = '';

            price = element.selectedOptions[0].getAttribute('data-regular-price');
            eventDate.querySelector('.event-date-price .special-price .regular-price .price').innerHTML = price;

            price = element.selectedOptions[0].getAttribute('data-special-price');
            eventDate.querySelector('.event-date-price .special-price .special-price .price').innerHTML = price;
        } else {
            eventDate.querySelector('.event-date-price .regular-price').style.display = 'block';
            eventDate.querySelector('.event-date-price .special-price').style.display = 'none';

            eventDate.querySelector('.event-date-price .special-price .title').innerHTML = '';

            price = element.selectedOptions[0].getAttribute('data-regular-price');
            eventDate.querySelector('.event-date-price .regular-price .price').innerHTML = price;

            eventDate.querySelector('.event-date-price .special-price .regular-price .price').innerHTML = '';
            eventDate.querySelector('.event-date-price .special-price .special-price .price').innerHTML = '';
        }
    }

})();
