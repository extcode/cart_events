(() => {
  const eventDates = document.getElementsByClassName('event-event-date');

  for (let eventDateCount = 0; eventDateCount < eventDates.length; eventDateCount++) {
    const addToCartForms = eventDates[eventDateCount].querySelectorAll('form.add-to-cart-form');
    for (let addToCartFormsCount = 0; addToCartFormsCount < addToCartForms.length; addToCartFormsCount++) {
      const priceCategorySelects = addToCartForms[addToCartFormsCount].querySelectorAll('select.price-category-select');
      for (let priceCategorySelectCount = 0; priceCategorySelectCount < priceCategorySelects.length; priceCategorySelectCount++) {
        priceCategorySelects[priceCategorySelectCount].addEventListener('change', function () {
          updatePriceCategory(this, eventDates[eventDateCount])
        }, false);
      }
    }
  }

  function dispatchCustomEvent(name, dataObject) {
    const customEvent = new CustomEvent(
      name,
      {
        bubbles: true,
        cancelable: true,
        detail: dataObject
      }
    );
    document.dispatchEvent(customEvent);
  }

  function updatePriceCategory(element, eventDate) {
    const title = element.selectedOptions[0].getAttribute('data-title');

    if (title) {
      eventDate.querySelector('.event-date-price .regular-price').style.display = 'none';
      eventDate.querySelector('.event-date-price .special-price').style.display = 'block';

      eventDate.querySelector('.event-date-price .special-price .title').innerHTML = title;

      eventDate.querySelector('.event-date-price .regular-price .price').innerHTML = '';

      eventDate.querySelector('.event-date-price .special-price .regular-price .price').innerHTML =
        element.selectedOptions[0].getAttribute('data-regular-price');

      eventDate.querySelector('.event-date-price .special-price .special-price .price').innerHTML =
        element.selectedOptions[0].getAttribute('data-special-price');
    } else {
      eventDate.querySelector('.event-date-price .regular-price').style.display = 'block';
      eventDate.querySelector('.event-date-price .special-price').style.display = 'none';

      eventDate.querySelector('.event-date-price .special-price .title').innerHTML = '';

      eventDate.querySelector('.event-date-price .regular-price .price').innerHTML =
        element.selectedOptions[0].getAttribute('data-regular-price');

      eventDate.querySelector('.event-date-price .special-price .regular-price .price').innerHTML = '';
      eventDate.querySelector('.event-date-price .special-price .special-price .price').innerHTML = '';
    }

    dispatchCustomEvent(
      'extcode:update-price-category',
      {
        element, eventDate
      }
    );
  }
})();
