<!DOCTYPE html>
<html>

<head>
    <title>📆 Calendar Event Management 📆</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your custom CSS file -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <?php
    include('dbcon.php');
    $query = $conn->query("SELECT * FROM events ORDER BY id");
    ?>
    <script>
    $(document).ready(function() {
        var calendar = $('#calendar').fullCalendar({
            editable: true,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            timeFormat: 'h:mm a',
            events: [<?php while ($row = $query->fetch_object()) { ?> {
                    id: '<?php echo $row->id; ?>',
                    title: '<?php echo $row->title; ?>',
                    start: '<?php echo $row->start_event; ?>',
                    end: '<?php echo $row->end_event; ?>',
                },
                <?php } ?>
            ],
            selectable: true,
            selectHelper: true,

            select: function(start, end, allDay) {
                var title = prompt("Enter Event Title");
                if (title) {
                    var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                    $.ajax({
                        url: "insert.php",
                        type: "POST",
                        data: {
                            title: title,
                            start: start,
                            end: end
                        },
                        success: function(data) {
                            calendar.fullCalendar('refetchEvents');
                            alert(" Added Successfully✅");
                            window.location.replace("index.php");
                        }
                    })
                }
            },

            editable: true,
            eventResize: function(event) {
                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                var title = event.title;
                var id = event.id;
                $.ajax({
                    url: "update.php",
                    type: "POST",
                    data: {
                        title: title,
                        start: start,
                        end: end,
                        id: id
                    },
                    success: function() {
                        calendar.fullCalendar('refetchEvents');
                        alert('Event Update 🔄');
                    }
                })
            },

            eventDrop: function(event) {
                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                var title = event.title;
                var id = event.id;
                $.ajax({
                    url: "update.php",
                    type: "POST",
                    data: {
                        title: title,
                        start: start,
                        end: end,
                        id: id
                    },
                    success: function() {
                        calendar.fullCalendar('refetchEvents');
                        alert("Event Updated 🔄");
                    }
                });
            },

            eventClick: function(event) {
                if (confirm("Are you sure you want to remove it❓")) {
                    var id = event.id;
                    $.ajax({
                        url: "delete.php",
                        type: "POST",
                        data: {
                            id: id
                        },
                        success: function() {
                            calendar.fullCalendar('refetchEvents');
                            alert("Event Removed ❌");
                        }
                    })
                }
            },

            eventMouseover: function(event, jsEvent, view) {
                // Display the full title of the event as a tooltip
                var tooltip =
                    '<div class="tooltipevent" style="width:auto;height:auto;background:#fff8dc;position:absolute;z-index:10001;padding:10px;border:1px solid #ddd;border-radius:10px;">' +
                    event.title + '</div>';
                $("body").append(tooltip);
                $(this).mouseover(function(e) {
                    $(this).css('z-index', 10000);
                    $('.tooltipevent').fadeIn('500');
                    $('.tooltipevent').fadeTo('10', 1.9);
                }).mousemove(function(e) {
                    $('.tooltipevent').css('top', e.pageY + 10);
                    $('.tooltipevent').css('left', e.pageX + 20);
                });
            },

            eventMouseout: function(event, jsEvent, view) {
                // Remove the tooltip when the mouse leaves the event
                $(this).css('z-index', 8);
                $('.tooltipevent').remove();
            }

        });
    });
    </script>
</head>

<body>
    <br />
    <h2 align="center"><a href="">📆 Calendar Event Management System 📆</a></h2>
    <br />
    <div class="container">
        <div id="calendar"></div>
    </div>
</body>

</html>