<html>

<head><script>
$.getJSON("http://en.wikipedia.org/w/api.php?action=query&format=json&callback=?", {
    titles: "India",
    prop: "pageimages",
    pithumbsize: 150
  },
  function(data) {
    var source = "";
    var imageUrl = GetAttributeValue(data.query.pages);
    if (imageUrl == "") {
      $("#wiki").append("<div>No image found</div>");
    } else {
      var img = "<img src=\"" + imageUrl + "\">"
      $("#wiki").append(img);
    }
  }
);

 function GetAttributeValue(data) {
  var urli = "";
  for (var key in data) {
    if (data[key].thumbnail != undefined) {
      if (data[key].thumbnail.source != undefined) {
        urli = data[key].thumbnail.source;
        break;
      }
    }
  }
  return urli;
}
</script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
</head>

<body>
  <div id="wiki"></div>
</body>

</html>
