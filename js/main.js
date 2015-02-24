
$(function () {
  $("#body").addClass('load');
  onChange();
  onFileselect();  
});

function onChange() {
  $(document).on('change', '.btn-file :file', function () {
    var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
  });
}

function onFileselect() {
  $(document).ready(function () {
    $('.btn-file :file').on('fileselect', function (event, numFiles, label) {
      console.log(numFiles);
      console.log(label);
      
      $(this).parent().remove(".file-label");
      $(this).parent().append('<span class="file-label">'+ label +'</span>');
    });
  });
}