$(function () {
  add_bread_handler();
  add_topping_handler();
  
});

function add_bread_handler() {
  $('#addbread').on('click', function () {
    bread_name = $('#name').val();
    bread_price = $('#price').val();
    $.ajax({
      url: '/bread/add',
      success: function (data) {
        bread_obj = $.parseJSON(data);
        err = bread_obj.error;
        if (err === 1) {
          ui_output_messages(bread_obj.messages);
        } else {
          ui_add_bread(bread_obj);
        }
      },
      type: 'POST',
      data: {name: bread_name, price: bread_price}
    })
    .done(function () {
      $('#name').val('');
      $('#price').val('');
    })
    .fail(function () {
      console.log("error");
    })
    .always(function () {
      console.log("complete");
    }); // ajax call            

  }); // on click

}

function add_topping_handler() {
  $('#addtopping').on('click', function () {
    topping_name = $('#tname').val();
    topping_price = $('#tprice').val();
    $.ajax({
      url: '/topping/add',
      success: function (data) {
        topping_obj = $.parseJSON(data);
        err = topping_obj.error;
        if (err === 1) {
          ui_output_messages(topping_obj.messages);
        } else {
          ui_add_topping(topping_obj);
        }
      },
      type: 'POST',
      data: {name: topping_name, price: topping_price}
    })
    .done(function () {
      $('#tname').val('');
      $('#tprice').val('');
    })
    .fail(function () {
      console.log("error");
    })
    .always(function () {
      console.log("complete");
    }); // ajax call            

  }); // on click
}

function ui_output_messages(messages) {
  str = '';
  $.each(messages, function () {
    str += (this) + '\n';
  });
  alert(str);
}

function ui_add_bread(bread) {
  counter = bread.count;

  $remove_span = $('<span class="btn btn-danger btn-small"><a href="#"> X </a></span>');
  price = bread.price / 100;
  $price_span = $('<span class="floatright bread" data-breadid="' + bread.id + '"> €' + price.toFixed(2) + '</span>')
  $name_span = $('<span> ' + bread.name + '</span>');

  if (counter % 2 == 0) {
    $container = $('<div class="bread even"></div>');
  } else {
    $container = $('<div class="bread odd"></div>');
  }

  $container
    .append($remove_span)
    .append($price_span)
    .append($name_span)
    .appendTo($('#breads'));

}

function ui_add_topping(topping) {
  counter = topping.count;

  $remove_span = $('<span class="btn btn-danger btn-small"><a href="#"> X </a></span>');
  price = topping.price / 100;
  $price_span = $('<span class="floatright topping" data-toppingid="' + topping.id + '"> €' + price.toFixed(2) + '</span>')
  $name_span = $('<span> ' + topping.name + '</span>');

  if (counter % 2 == 0) {
    $container = $('<div class="topping even"></div>');
  } else {
    $container = $('<div class="topping odd"></div>');
  }

  $container
    .append($remove_span)
    .append($price_span)
    .append($name_span)
    .appendTo($('#toppings'));
}