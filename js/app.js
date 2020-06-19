/*Login or Registration Form Submit*/
$("#login_form,#edit_form, #addBook_form,#registration_form").submit(function (e) {
    e.preventDefault();
    var obj = $(this), action = obj.attr('name'); /*Define variables*/
    $.ajax({
        type: "POST",
        url: e.target.action,
        data: obj.serialize()+"&Action="+action,
        cache: false,
        success: function (JSON) {
            if (JSON.error != '') {
                $("#"+action+" #display_error").show().html(JSON.error);
            } else {
                window.location.href = "./";
            }
        }
    });
});
$(".delet").click(function (e){
    var obj = $(this), action = obj.attr('name'), id=obj.attr('id'); /*Define variables*/
    $.ajax({
        type: "POST",
        url: "submit.php",
        data: obj.serialize()+"&Action="+action+"&book_id="+id,
        cache: false,
        success: function (JSON) {
            if (JSON.error != '') {
                $("#"+action+" #display_error").show().html(JSON.error);
            } else {
                window.location.href = "./";
            }
        }
    });
});
$("#Extend_form").submit(function (e) {
    e.preventDefault();
    var obj = $(this), action = obj.attr('name'), id=obj.attr('class')
    console.log(id); /*Define variables*/
    $.ajax({
        type: "POST",
        url: e.target.action,
        data: obj.serialize()+"&Action="+action+"&id="+id,
        cache: false,
        success: function (JSON) {
            if (JSON.error != '') {
                alert(JSON.error);
                $("#"+action+" #display_error").show().html(JSON.error);
            } else {
                window.location.href = "./";
            }
        }
    });
});

    
$("#Borrowing_form,.editBook_form").submit(function (e) {
    e.preventDefault();
    var obj = $(this), action = obj.attr('name'); /*Define variables*/
    $.ajax({
        type: "POST",
        url: e.target.action,
        data: obj.serialize()+"&Action="+action,
        cache: false,
        success: function (JSON) {
            if (JSON.error != '') {
                alert(JSON.error);
                $("#"+obj.attr('id')+" #display_error").show().html(JSON.error);
            } else {
                window.location.href = "./Books.php";
            }
        }
    });
});

$(document).ready(function(){
    var date_input=$('input[name="date"]'); //our date input has the name "date"
    var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
    date_input.datepicker({
        format: 'mm/dd/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
    })
});
function getBooks(type,val,user_type){
    $.ajax({
        type: 'POST',
        url: 'getData.php',
        data: 'type='+type+'&val='+val+'&user_type='+user_type,
        beforeSend:function(html){
            $('.loading-overlay').show();
        },
        success:function(html){
            $('.loading-overlay').hide();
            $('#booksData').html(html);
        }
    });
}

var edit = true;
function inputToggle(e) {
            e.preventDefault();
            edit = !edit
            $('#Name').prop('readonly', edit);
            $('#Email').prop('readonly', edit);

 }
 var pass = true;
function  passwordToggle(e) {
            e.preventDefault();

            $('#Password').prop('readonly', pass = !pass);

 }
$(document).ready(function(){
  $('.search-box input[type="text"]').on("keyup input", function(){
     /* Get input value on change */
      var inputVal = $(this).val();
      var resultDropdown = $(this).siblings(".result");
       if(inputVal.length){
        $.get("inc/functions.php", {term: inputVal}).done(function(data){
          // Display the returned data in browser
          resultDropdown.html(data);
          });
            }else{
           resultDropdown.empty();
            }
            });
                
            // Set search input value on click of result item
            $(document).on("click", ".result p", function(){
            $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
                    $(this).parent(".result").empty();
            });
            });