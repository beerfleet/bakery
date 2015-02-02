$(function () {
  add_bread_handler();
});

function add_bread_handler() {
  $('#addbread').on('click', function () {
    bread_name = $('#name').val();
    bread_price = $('#price').val();
    $.ajax({
      url: '/bread/add',
      type: 'POST',
      data: {name: bread_name, price: bread_price},
    })
            .done(function () {
              console.log("success");
            })
            .fail(function () {
              console.log("error");
            })
            .always(function () {
              console.log("complete");
            });
}
);
  