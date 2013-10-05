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
    var userUrl = "/inc/ajax.user.php",
        $words = $("#words");
    $words.on("click", ".delete a.d", function(){
      if(!confirm($("#confirmMsg").text())){
        return false;
      }
      var o = $(this),
        data = {
          id : o.attr("href").replace("#", ""),
          lang : getLang(),
          act : 1
      };
      $.getJSON(userUrl, data, function(json) {  
          if(json.err === 0){
            o.parent().parent().hide(1000);
          }else{
            showStatus(json); 
          }
      }); 
    });

    $words.on("click", ".edit a.e", function(){
      var $tr = $(this).parent().parent(),
          $tds = $tr.find("td"),
          $qTD = $tds.eq(0),
          $aTD = $tds.eq(1),
          qText = $qTD.text(),
          aText = $aTD.text();

          $tr.addClass("editing");
          $qTD.html(makeInput("question", qText));
          $aTD.html(makeInput("answer", aText));
          
          $words.find("a").addClass("hidden");
          $tds.eq(2).append('<a href="#" title="Uložiť / Save" class="save"></a>');
          $tds.eq(3).append('<a href="#" title="Zrušit / Cancel" class="back"></a>');

          $tr.on("click", ".back", function(){
              finishEditing(qText, aText);
              return false;
          });

          $tr.on("click", ".save", processSave);

          function makeInput(name, val){
            return '<input type="text" value="'+val+'" name="'+name+'" />';
          }

          function finishEditing(qT, aT){
            $qTD.html('').text(qT);
            $aTD.html('').text(aT);
            $tr.removeClass("editing");
            $tr.find(".save").remove();
            $tr.find(".back").remove();
            $words.find("a.hidden").removeClass("hidden");
          }


          function processSave(){
            var data = {
                  id :  $tr.attr("id").replace("id", ""),
                  lang : getLang(),
                  question : $tds.eq(0).find("input").val(),
                  answer : $tds.eq(1).find("input").val(),
                  act : 2
              };
            if(!areDataSet(data)){
              return false;
            }
            $.getJSON(userUrl, data, function(json) {  
                if(json.err === 0){
                  finishEditing(data.question, data.answer);
                }else{
                  showStatus(json); 
                }
            }); 
            return false;
          }
    });

    $(document).on("click", ".addNewWord .btn", addNewWord);
    $(document).on("keypress", function(e) {
        if(e.which == 13) {
            return addNewWord();
        }
    });

    function addNewWord(){
       var data = {
           question : $.trim($("#addNewWord .q").val()),
           answer : $.trim($("#addNewWord .a").val()),
           lang : getLang(),
           importId : parseInt($("#importId2").text(), 10),
           act : 3
       };
      if(!areDataSet(data)){
        return false;
      }
      $.getJSON(userUrl, data, function(json) {  
        if(json.err === 0){
           data.id = json.id;
           appendRow(data);
           $("#addNewWord .q").val('').focus();
           $("#addNewWord .a").val('');
        }else{
          showStatus(json); 
        }
      }); 

      function appendRow(data){
        if($words.find("tr").length === 0){
          $words.html(row(data));
        }else{
          $words.append(row(data));
        }
        function row(data){
          return '<tr id="id'+ data.id +'">'+
                    '<td>'+data.question+'</td>'+
                    '<td>'+data.answer+'</td>'+
                    '<td class="edit"><a class="e" href="#'+ data.id +'" ></a></td>'+
                    '<td class="delete"><a class="d" href="#'+ data.id +'" ></a></td>'+  
                '</tr>';
        }
      }

      return false;
    }
});

function areDataSet(data){
  if(data.question.length === 0 || data.answer.length === 0){
    return false;
  }
  return true;  
}

function getLang(){
  return $("#words").attr("data-lang");
}

jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", (($(window).height() - this.outerHeight()) / 2) + $(window).scrollTop() + "px");
    this.css("left", (($(window).width() - this.outerWidth()) / 2) + $(window).scrollLeft() + "px");
    return this;
}
