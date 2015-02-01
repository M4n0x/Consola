/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 $('#FormSearch').submit(function() {
 $('input[name,value=""]').attr('name', '');
 });
 */

$('#FormSearch').submit(function() {
    $(this).find('input:not([type="radio"]):not([type="checkbox"]),select:not(:has(option:selected[value!=""]))').each(function() {
        if (!$(this).val()) {
            $(this).attr('name', '');
        }
    });
});

$(document).on("change", "#IS_LICENSED", function() {
    lockModal(!$(this).prop('checked'));
});

function lockModal(param) {
    if (!param) {
        $(".form input:disabled, .form textarea:disabled, .form select:disabled").prop("disabled", param);
        return;
    } else {
        $("input[name=BUY_VERSION]").prop("disabled", true);
        $("input[name=REF_SUPPLIER]").prop("disabled", true);
        $("input[name=BUY_DATE]").prop("disabled", true);
        $("input[name=KEY_ACTIVATION]").prop("disabled", true);
    }
}

function cleanAlert() {
    $("#modal-alert, .alert-box").fadeOut(function(){$(this).remove();});
}

function alertMsg(message, type) {
    var container ="";
    
    if (message.length === 0) {
        return;
    }

    cleanAlert();
    
    container = '<div class="alert-box">';
    container = container + '<div id="modal-alert" class="msg msg-' + type + '" >';
    container = container + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
    container = container + '<div class="icon"><i></i></div>';
    container = container + '<ul class="msg-list">';
    container = container + '<li> ' + message + '</li></ul></div></div>';
    $("body").prepend(container);
}

(function($) {

    $.fn.autocomplete = function() {
        var $this = $(this);
        var oldvalue;

        //disable autocomplete from browser
        $this.attr('autocomplete', 'off');

        $(this).parent().append("<ul class='autocomplete-main hidden'></ul>");
        $(this).bind("keyup", function(e) {

            var value = $(this).val().trim();
            var key = e.keyCode || e.which;
            if (key === 38 || key === 40 || key === 13) {
                return;
            }

            if (value === oldvalue || value.length < 2) {
                //alert("test");
                return;
            } else {
                oldvalue = value;
            }

            if (value === "") {
                $(this).parent().children(".autocomplete-main").addClass("hidden");
                return;
            }

            $.ajax({
                type: "POST",
                url: "autocomplete/" + $(this).attr("data-db"),
                data: {term: value},
                dataType: 'json',
                success: function(data) {
                    setresults($this, data);
                    $this.parent().children(".autocomplete-main").removeClass("hidden");
                }// end success function
            });

        });

        $(this).parent().on("mouseover", "li", function() {
            $(this).parent().children("li").removeClass("selected");
            $(this).addClass("selected");

        });

        $(this).parent().on("mousedown", "li", function() {
            $(this).parents(".autocomplete-group").children("input").val($(this).text());
            hideresult($(this).parent());
        });


        $(this).bind("blur", function() {
            var $this_blur = $(this);
            hideresult($this_blur);
        });

        function hideresult(selResult) {
            selResult.parent().children(".autocomplete-main").addClass("hidden");
        }

        $(this).bind("keydown", function(e) {
            if ($this.parent().children(".autocomplete-main").hasClass("hidden")) {
                return;
            }
            if (e.keyCode === 40) { //upkey
                var parent = $this.parent().children(".autocomplete-main");
                var selected = parent.children(".selected");
                var nextItem;
                if (selected.attr("class") !== "autocomplete-item selected") {
                    selected = parent.children("li").first();
                    selected.addClass("selected");
                    $this.val(selected.text());
                    return;
                }

                selected.removeClass("selected");
                nextItem = selected.next();
                if (nextItem.length === 0) {
                    nextItem = parent.children("li").first();
                }
                nextItem.addClass("selected");
                $this.val(nextItem.text());
            }
            if (e.keyCode === 38) { //downkey
                var parent = $this.parent().children(".autocomplete-main");
                var selected = parent.children(".selected");
                var prevItem;
                if (selected.attr("class") !== "autocomplete-item selected") {
                    selected = parent.children("li").last();
                    selected.addClass("selected");
                    $this.val(selected.text());
                    return;
                }

                selected.removeClass("selected");
                prevItem = selected.prev();
                if (prevItem.length === 0) {
                    prevItem = parent.children("li").last();
                }
                prevItem.addClass("selected");
                $this.val(prevItem.text());
            }

            if (e.keyCode === 13) {
                $this.parent().children(".autocomplete-main").addClass("hidden");
                return;
            }
        });
    };

    function setresults($this, donnees) {
        var transformresult = "";
       
        $.each(donnees.ldata, function(i, item) {
            transformresult += "<li class='autocomplete-item'>" + item + "</li>";
        });

        if (transformresult === "") {
            transformresult += "<li class='autocomplete-item'>No results</li>";
        }
        $this.parent().children(".autocomplete-main").empty().append(transformresult);
    }


})(jQuery);

$(document).on("click", ".modal-link", function(e) {
    e.preventDefault();
    var link = $(this).attr("href");
    
    if (link == '') {
        return;
    }
    
    $.ajax({
        url: link,
        type: "get",
        beforeSend: function() {
            alertMsg("Veuillez patienter pendant le chargement...", "load");
        },
        success: function(donnees) {
            cleanAlert();
            openModal(donnees);
        }
    });
});

$(document).on("submit", ".modal-dialog form", function(e) {
    e.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        dataType: 'json',
        data: $(this).serialize(),
        beforeSend: function() {
            alertMsg("chargement...", "load");
        },
        success: function(data) {
            if (data.error === 0) {
                alertMsg(data.msg, "valid");
                closeModal();
                location.reload();
            } else {
                alertMsg(data.msg, "error");
                lightValueModal(data);
            }           
        },
        error: function (jqXHR, exception) {
            alertMsg("Il semblerait qu'une erreur se soit produite... Veuillez contacter votre administrateur si l'erreur persiste.", "error");
        }
    });
    return false;
});

$(document).on("click", ".modal-dialog .btn", function(e) {
    if ($(this).attr("data-dismiss") === "true") {
        closeModal();
    }
});

function lightValueModal(donnees) {
    var tag = "has-error";
    $("." + tag).removeClass(tag)
    $.each(donnees.ldata.error, function(index, value) {
        $(".form input[name=" + value + "], .form textarea[name=" + value + "], .form select[name=" + value + "]").parent().addClass(tag);
    });   
}

function openModal(data) {
    $("body").prepend("<div id=\"modal-background\"></div> <div id=\"modal-content\"></div>");
    $("#modal-content").empty().append(data);
    $("body").css("overflow", "hidden");
    $("#BUY_DATE").datepicker();
    $("#CATALOG_ID").autocomplete();
    $("#ASSET_ID").autocomplete();
}

function closeModal() {
    $("#modal-background, #modal-content").remove();
    $("body").css("overflow", "auto");
}

$(document).ready(function() {
        setTimeout(function() {
        cleanAlert();
    },5000);
});