$(function() {
  checkboxHandler();
});

function checkboxHandler() {
  $('.enabled').on('change', function() {    
    checked = $(this).is(':checked') === true ? 1 : 0;    
    userid = $(this).data('userid');
    console.log(userid);
    
    $.ajax({
      url: '/admin/user/'+ userid +'/enable/' + checked,
    })
    .done(function() {
      console.log("success");
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });
    
  });
}