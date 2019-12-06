var core = function () {
  //variable initialation
  var controller = '';
  var base_url = '';

  var request = function(target, form_data){
    return  $.ajax({
                     type: "POST",
                     url:  target,
                     dataType: "json",
                     data: form_data,
                     error: function(jqXHR, lsStatus, ex) {
                       console.log(JSON.stringify(ex));
                     },
                     success: function(response){}
                    });
  }

  var speak = function(speech){
    var msg = new SpeechSynthesisUtterance();
    var voices = window.speechSynthesis.getVoices();
    speechSynthesis.cancel();
    msg.voice = voices[0];
    msg.rate = 1;
    msg.pitch = 1;
    msg.text = speech;
    speechSynthesis.speak(msg);
  }

  var dataTables = function(target,id, disabled)
  {
    return $('#'+id).DataTable({
                      "processing": true,
                      "serverSide": true,
                      "order": [],
                      "ajax":{
                               url : target, // json datasource
                               type: "post",  // method  , by default get
                               data: function ( d ){
                                  d.token = $('#token').val();
                                  d.csrf_token = $('#token').val();
                               },
                               error: function(){  // error handling
                                   $("."+id+"-grid-error").html("");
                                   $("#"+id+"-grid").append('<tbody class="'+id+'-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                                   $("#"+id+"-grid_processing").css("display","none");
                               },
                               dataSrc: function ( json ) {
                                    //Make your callback here.
                                    //set_token(json.generated_token.hash);
                                    return json.data;
                                }
                             },
                      "columnDefs": [
                                        { 
                                           "targets":  disabled , //first column / numbering column
                                           "orderable": false, //set not orderable
                                        },
                                     ],
                   });// end of data table initialize 660 035 273 - EZSERVER
                      //EZBANKING-CON 

  }

  // var add_form_token = function(form_data)
  // {
  //   return form_data;
  // }
  // var get_token = function()
  // {
  //   return {'token': $('#token').val(),'csrf_token': $('#token').val()}
  // }  

  var loader = function(id)
  {
    $('#'+id).parent().before('<div class="preloader pl-size-xs"><div class="spinner-layer pl-light-blue"><div class="circle-clipper left"><div class="circle"></div></div></div></div>');
  }

  var removeLoader = function(id)
  {
    $('#'+id).parent().parent().find('.preloader').remove('.preloader');
  }

  var init = function(){
    base_url = $("#base_url").val();
    controller = $("#controller").val();
  }

  // var set_token = function(token){
  //     $('#token').val(token);
  // };



  return {

    init: function () {
          init(); 
    },
    request: function(controller, data) {
      return request(controller, data);
    },
    // add_form_token: function(){
    //     add_form_token();
    // },
    // get_token : function(){
    //   get_token();
    // },
    speak: function(speech){
      speak(speech)
    },
    // set_token: function(token){
    //   set_token(token);
    // },
    loader: function(id){
      loader();
    },
    removeLoader: function(id){
      removeLoader(id);
    },
    dataTables: function(target,id, disabled){
      dataTables(target,id, disabled);
    }
  };
}();



