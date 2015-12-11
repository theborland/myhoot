<html><head>
<script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
<script>

        var conn = new ab.Session('ws://192.168.0.103:8080',
        function() { 
            conn.subscribe('apple', function(topic, data) {
                // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
                console.log('New article published to category "' + topic + '" : ' + data.title);
            	var container = document.getElementById("names");
				container.innerHTML = container.innerHTML  + "<br>"+data.title;
            });
        },
        function() {
            console.warn('WebSocket connection closed');
        },
        {'skipSubprotocolCheck': true}
    );
</script>

</head>
<body>Doing something?
<div id="names">
</div>
</body>
</html>
