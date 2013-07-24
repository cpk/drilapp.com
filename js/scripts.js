function createClasses(){
	$('tr:odd').addClass('odd');
	$('tr:event').removeClass('odd');
} 
function showStatus(data){
	var html = '<p class="'+ (data.err === 0 ? "ok" : "err") +'">'+ data.msg +'</p>',
	o = $("#status");
	o.html(html).center().fadeIn();
	setTimeout(function() {o.fadeOut(100);}, 4000);
}

function validate(f){
	var inputs = f.find('input.required, textarea.required'),
	valid = true,

	vldt = {
		required : function(v,i) {return {r : !!v ,  msg : 'Nie sú výplnené povinné hodnoty'};},
		email	 : function(v,i) {return {r : v.match( /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/ ), msg : 'Neplatná e-mailová adresa'};},
		fiveplus : function(v,i) {return {r : v.length >= 5, msg : 'Hodnota musí mať min. 5 znakov'} ;},
		numeric  : function(v,i) {return {r : !isNaN(v), msg : 'Hodnota '+v+' nie je číslo.'} ;},
		mobil	 : function(v,i) {return {r : v.length > 8 && v.length <=10,  msg : 'Hodnota musí mať 9-10 znakov'} ;}
	};
	inputs.removeClass('formerr');
	inputs.each(function(){
		var input = $(this),
			val = input.val(),
			cls = input.attr("class").split(' ');

		for(i in cls){
			if(vldt.hasOwnProperty(cls[i])){
				var res = vldt[cls[i]](val,input);
				if(!res.r){
					input.addClass('formerr');
					showStatus({err : 1, msg : res.msg});
					valid = false;
				}
			}
		}
	});
	return valid;	
}

function is_numeric(n){
    return !isNaN(parseInt(n)) && isFinite(n);
}

function renameArr(a){
	var d = {};	
	for (i in a) {
		d[a[i].name] = a[i].value;
	}
	return d;
}
function reindex(){
    var o = $('.no');
    $.each(o, function(i, v){
        o.eq(i).text(i+1);
    });
    
}

function addFiled(index, answer, question){
    var table = $('#table tbody'),
        filed = '<tr><td class="w50"><span class="no">'+(index+2)+
                '</span></td><td><input type="text" value="'+question+'" name="question[]" maxlength="255" />'+
                '</td><td><input type="text" value="'+answer+'" name="answer[]"  maxlength="255" /></td>' +
                '<td class="w50"><a href="#" class="del" tabindex="-1"></a></td></tr>'  
        table.append(filed);         
}

function saveJSON(json){
  if(json.data.length > 0);
  localStorage.setItem("draft", JSON.stringify(json));
}

function loadJSON(){
 var data = localStorage.getItem("draft");
 if(data !== null){
    return $.parseJSON(data);
 } 
 return { "data" : [] };
}

function loadWords(){
  var json = loadJSON() , idx = 0;
  for(var i in json.data){
      var item = json.data[i];
      if(idx === 0) {
        $('#table tr .fq').val(item.question);
        $('#table tr .fa').val(item.answer);
      }else{
        addFiled(idx, item.answer, item.question);
      }
      idx++;
  }
}

function getData(forLocalStorage){
  var json = {}, 
      counter = 0,
      serializedForm = $('form.ajax').serializeArray();
      $.each(serializedForm, function(i,v){
          if(v.name === "question[]" || v.name === "answer[]"){  
            if(json[counter] === undefined) json[counter] = { question : '', answer: '' };
            if((i % 2 === 0)){
              json[counter].question = v.value;
            }else{
              json[counter].answer = v.value;
               if(i !== 0) counter++;
            }
          }
      });
     if(forLocalStorage){
        var localStorageData = [];
        for(var i in json){
          if(json[i].question.length > 0 || json[i].answer.length > 0){
            localStorageData.push({ "question" : json[i].question , "answer" : json[i].answer});
          }
        }
        return localStorageData;
     }
     return json;
}


function saveWords(){
    saveJSON( { "data" : getData(true) } );
}

function deleteWords(){
    saveJSON( { "data" : [] } );
}


$(function() {
 $('.lightbox').lightBox();
 $('.focus').focus();
 $('#mobile').fadeIn(500);
  loadWords();
 $("table").delegate("table tr:last-child input", "focus", function() {
     addFiled($(this).parent().parent().index(), '', '');
  });

 $("table").delegate("input", "change", function() {
     saveWords();
  });


  
   $('#share-btn').click(function(){
        $(this).hide(500);
        $('#share-form').show(200);
        $('input[name=share]').prop('checked', true);
        return false;
     });
        
 
 $("table").delegate("table .del", "click", function() {
     $(this).parent().parent().remove();
     reindex();
     saveWords();
     return false;
  });
    
  $('.app .qrbox span').click(function(){
      var o = $(this); qr = o.parent();
      if(qr.hasClass('white')){
          qr.removeClass('white');
           if(o.text()=== 'zobraz'){ o.text('skryť');}
          else if(o.text()=== 'show') o.text('hide');
      }else{
          qr.addClass('white');
          
          if(o.text()=== 'skryť'){ o.text('zobraz');}
          else if(o.text()=== 'hide') o.text('show');
      }
      return false;
  }); 


  
  $("#slider").easySlider({auto: true, continuous: true });
  $('#tabs').easytabs(); 
  
  $('.ajax').submit(function(){
      var data = {};    
      data.act = 1;
      data.data = getData(false);
      data.email = $('input[name=email]').val();
      data.lang = $('input[name=lang]').val();
      data.captcha = $('input[name=captcha]').val();
      if ($('input[name=share]').is(':checked')) {
          data.share = 1;
          data.lang2 = $('select[name=lang2] option:selected').val();
          data.level = $('select[name=level] option:selected').val();
          data.name = $('input[name=name]').val();
          data.author = $('input[name=author]').val();
          data.descr = $('textarea[name=descr]').val();
          data.lang_a = $('select[name=lang_a] option:selected').val();
      }else{
         data.share = 0; 
      }
      
      if(data.captcha.length === 0){ 
          var e = (data.lang === "sk" ? 'Opíšte text obrázka' : 'Fill captcha please.');
          showStatus({ err : 1, msg : e});
      }else{ 
          $.post('/inc/ajax.php', data, function(response) { 
            if(response.err === 0){
                $('form.ajax').html('<p class="ok">'+response.msg + '</p>');
                $('#infoImport').show();
                deleteWords();
            }
            showStatus(response);
        }, "json");
      }
      return false;
  });
       //  $('.thumb').lightbox();
       // AUTOCOMPLETE -----------------------------------------------------------
/*	$( "input[name=q]" ).autocomplete({
            focus: function( event, ui ) {
                   $( "input[name=q]" ).val( ui.item.label );
                   return false;
           },
            source: function(reques, response){  
                    reques.act = 1;
                    $.getJSON('/inc/ajax.php?cb=?', reques, function(data) {  
                    response( $.map( data, function( item ) {
                                return {value: item.value}
                        }));
                    });  
           },
           minLength: 1,
           select: function( e, ui ) {  ui.item.value; }
	});
       
       $('input[name=q]').click(function(){
          if($(this).val() === 'Vyhľadať náradie')
                            $(this).val('');
       });
       
       var li = $( "#slider ul li:last");
       var text = li.text();
       if(text.length === 0){
           li.hide();
       }
       */
       
});


jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", (($(window).height() - this.outerHeight()) / 2) + $(window).scrollTop() + "px");
    this.css("left", (($(window).width() - this.outerWidth()) / 2) + $(window).scrollLeft() + "px");
    return this;
}
