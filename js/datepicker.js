/* 
 * Author: Steve Reis
 * Description : A simple date picker...
 * How to use : wrap input into a dov and in js file add $("#myinput").datepicker(); 
 * required : jquery.js, datepicker.css
 */
(function($) {

    $.fn.datepicker = function() {
        //disable autocomplete 
        $(this).attr('autocomplete', 'off');
        var $this = $(this);
        var currDate = new Date();
        var Jump = 0;
        var AppendToThis = $(this).parent();
        var MonthName = ["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre", "Octobre","Novembre", "Décembre"];
        
        var contents = {
            wrapper: "<div class='datepicker-wrapper' style='display:none;'></div>",
            header: "<div class='datepicker-header'>"
                    + "<div class='datepicker-prec'><</div>"
                    + "<div class='datepicker-month'></div>"
                    + "<div class='datepicker-next'>></div>"
                    + "</div>",
            tableheader: '<table class="table-header">' +
                    '<thead><tr>' +
                    '<th>Lun</th>' +
                    '<th>Mar</th>' +
                    '<th>Mer</th>' +
                    '<th>Jeu</th>' +
                    '<th>Ven</th>' +
                    '<th>Sam</th>' +
                    '<th>Dim</th>' +
                    '</tr></thead><tbody></tbody></table>',
            tablecontent: ""
        };

        AppendToThis.append(contents.wrapper);
        AppendToThis.children(".datepicker-wrapper").append(contents.header);
        var $thiswrap = AppendToThis.children(".datepicker-wrapper");
        $thiswrap.append(contents.tableheader);
        GenContent(currDate);
        setContent();
        
        function setContent() {
            $thiswrap.find("table > tbody").empty().append(contents.tablecontent);
            $thiswrap.find(".datepicker-month").text(MonthName[currDate.getMonth()] + " " + currDate.getFullYear());
        }

        function GenContent() {
            var NumDays = daysInMonth(currDate.getFullYear(), currDate.getMonth());
            var BeginDay = FirstDayMonth(currDate.getFullYear(), currDate.getMonth());
            // DEBUG alert("NumDays -> " + NumDays + " || BeginDay ->" + BeginDay + "\n\n" + " Date -> " + new Date(currDate.getFullYear(), currDate.getMonth(), 1).getDay());
            if (BeginDay === 0) {BeginDay=1;} 
            var ModDay = 42 - (NumDays + BeginDay-1);
            var lastEvent;
            Jump = 0;
            contents.tablecontent= "<tr>";
            
            // start content end of previous month
            for (var i = 1; i <= BeginDay-1; i++)
            {
                //alert(((daysInMonth(currDate.getFullYear(), currDate.getMonth()-1)) + " - " + (BeginDay-1) + " + " +  i));
                contents.tablecontent += "<td class='datepiker-prevMonth' data-day='" + ((daysInMonth(currDate.getFullYear(), currDate.getMonth()-1))-(BeginDay-1)+i) + "'>" + ((daysInMonth(currDate.getFullYear(),currDate.getMonth()-1))-(BeginDay-1)+i) + "</td>";
                JumpRow();
            }

            //real content
            for (var i = 1; i <= NumDays; i++)
            {
                contents.tablecontent += "<td class='datepiker-currMonth' data-day='"+(i)+"'>" + (i) + "</td>";
                JumpRow();
            }
            
            //end content begin of next month
            for (var i = 1; i <= ModDay; i++)
            {
               contents.tablecontent += "<td class='datepiker-nextMonth' data-day='"+(i+1)+"'>" + i + "</td>";
               JumpRow();     
            }
            
            contents.tablecontent += "</tr>";
        }

        function daysInMonth(Year, Month) {
          return new Date(Year, Month+1,0).getDate();

        }

        function FirstDayMonth(Year, Month) {
            var numDay = new Date(Year, Month, 1).getDay();
            if (numDay === 0 || numDay ===1) {numDay += 7;}
            return numDay;

        }

        function JumpRow() {
            if (Jump === 6) {
                Jump = 0;
                contents.tablecontent += "</tr><tr>";
            } else {
                Jump++;
            }
        }
        
        function ChangeDate(mod){
            currDate.setMonth(currDate.getMonth() + mod);
        }
        
        $(document).on("mousedown", "",function(event) {
                var $target = $(event.target);
                if ($target.parents(".datepicker-wrapper").length === 0) {
                    $this.parent().children(".datepicker-wrapper").hide();
                }
        });
        
        $(document).on("click", ".datepicker-next, .datepiker-nextMonth", function() {
            ChangeDate(+1);
            GenContent(); 
            setContent();
        });
        
        $(document).on("click", ".datepicker-prec, .datepiker-prevMonth", function() { 
            ChangeDate(-1);
            GenContent(); 
            setContent();
        });
        
        $(document).on("mousedown", ".datepiker-currMonth", function() {
            currDate.setDate($(this).attr("data-day"));
            var month = (currDate.getMonth()+1);
            var day = currDate.getDate();
            month = month > 9 ? month : "0" + month ;
            day = day > 9 ? day : "0" + day ;
            $this.val((day + "." + month + "." + currDate.getFullYear()));
            $this.parent().children(".datepicker-wrapper").hide();
        });
        
        $(this).focus(function() {
            $(this).parent().children(".datepicker-wrapper").show();;
        });
    };
})(jQuery);