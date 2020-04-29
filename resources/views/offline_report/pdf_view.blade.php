<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <title>{{ $title }}</title>
 <style type="text/css">
html, body {
  font-size:100%; 
  font-family: verdana,arial,sans-serif;
  }

input[type="text"], input[type="submit"] {
  width:100%;
  font-size:1em;
  -moz-box-sizing:border-box; 
  -webkit-box-sizing:border-box; 
  box-sizing:border-box;
  }

input[type="text"] {
  border:1px solid #ccc;
  border-radius:3px; 
  padding:5px;
  }

th { font-size:80% line-height:110%; }
p, li, td { font-size:1em; line-height:120%; }
td { vertical-align:top;}
th { vertical-align:bottom; font-size:.8em; font-weight:bold; text-align:center; }
h1 { font-size:1.8em; line-height:100%; text-align:center; }
h2 { font-size:1.6em; }
h3 { font-size:1.4em; }
h4 { font-size:1.2em; }
h5 { font-size:1em; }
a { text-decoration:none; color:#1c5292; font-weight:bold; }
a, a img { border:none; outline:none; }
.content { position:relative; max-width:450px; margin:.5in auto; }
.tablediv { margin:.5in auto; display:table; }
.nowrap { white-space:nowrap; }
</style>
</head>
<body id="results">
  <h1>{{ $heading}}</h1>
  <div>
     <p>{{$content}}</p> 
  </div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
	// This function creates a standard table with column/rows
// Parameter Information
// objArray = Anytype of object array, like JSON results
// theme (optional) = A css class to add to the table (e.g. <table class="<theme>">
// enableHeader (optional) = Controls if you want to hide/show, default is show
function CreateTableView(objArray, theme, enableHeader) {
    // set optional theme parameter
    if (theme === undefined) {
        theme = 'lightPro'; //default theme
    }
 
    if (enableHeader === undefined) {
        enableHeader = true; //default enable headers
    }
 
    // If the returned data is an object do nothing, else try to parse
    var array = typeof objArray != 'object' ? JSON.parse(objArray) : new Array(objArray);
    var keys = Object.keys(array[0]);
 
    var str = '<table id="table" class="' + theme + '" border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;">';
 
    // table head
    if (enableHeader) {
        str += '<thead><tr>';
        for (var index in keys) {
            str += '<th scope="col">' + keys[index] + '</th>';
        }
        str += '</tr></thead>';
    }
 
    // table body
    str += '<tbody>';
    for (var i = 0; i < array.length; i++) {
        str += (i % 2 == 0) ? '<tr class="alt">' : '<tr>';
        for (var index in keys) {
            var objValue = array[i][keys[index]];
 
            // Support for Nested Tables
            if (typeof objValue === 'object' && objValue !== null) {
                if (Array.isArray(objValue)) {
                    str += '<td>';
                    for (var aindex in objValue) {
                        str += CreateTableView(objValue[aindex], theme, true);
                    }
                    str += '</td>';
                } else {
                    str += '<td>' + CreateTableView(objValue, theme, true) + '</td>';
                }
            } else {
                str += '<td>' + objValue + '</td>';
            }
 
        }
        str += '</tr>';
    }
    str += '</tbody>'
    str += '</table>';
 
    return str;
}
 
// This function creates a details view table with column 1 as the header and column 2 as the details
// Parameter Information
// objArray = Anytype of object array, like JSON results
// theme (optional) = A css class to add to the table (e.g. <table class="<theme>">
// enableHeader (optional) = Controls if you want to hide/show, default is show
function CreateDetailView(objArray, theme, enableHeader) {
    // set optional theme parameter
    if (theme === undefined) {
        theme = 'lightPro';  //default theme
    }
 
    if (enableHeader === undefined) {
        enableHeader = true; //default enable headers
    }
 
    // If the returned data is an object do nothing, else try to parse
    var array = typeof objArray != 'object' ? JSON.parse(objArray) : new Array(objArray);
    var keys = Object.keys(array[0]);
 
    var str = '<table id="table" class="' + theme + '" border="1" cellpadding="7" cellspacing="0" style="border-collapse:collapse;">';
    str += '<tbody>';
 
 
    for (var i = 0; i < array.length; i++) {
        var row = 0;
        for (var index in keys) {
            var objValue = array[i][keys[index]]
 
            str += (row % 2 == 0) ? '<tr class="alt">' : '<tr>';
 
            if (enableHeader) {
                str += '<th scope="row">' + keys[index] + '</th>';
            }
 
            // Support for Nested Tables
            if (typeof objValue === 'object' && objValue !== null) {
                if (Array.isArray(objValue)) {
                    str += '<td>';
                    for (var aindex in objValue) {
                        str += CreateDetailView(objValue[aindex], theme, true);
                    }
                    str += '</td>';
                } else {
                    str += '<td>' + CreateDetailView(objValue, theme, true) + '</td>';
                }
            } else {
                str += '<td>' + objValue + '</td>';
            }
 
            str += '</tr>';
            row++;
        }
    }
    str += '</tbody>'
    str += '</table>';
    return str;
}


// function CreateTableFromJson(objArray) {
//     // has passed in array has already been deserialized
//     var array = typeof  objArray != 'object' ? JSON.parse(objArray) : objArray;

//     var str = '<table class="tableNormal">';
//     str += '<tr>';
//     for (var index in array[0]) {
//         str += '<th scope="col">' + index + '</th>';
//     }
//     str += '</tr>';
//     str += '<tbody>';
//     for (var i = 0; i < array.length; i++) {
//         str += (i % 2 == 0) ? '<tr class="alt">' : '<tr>';
//         for (var index in array[i]) {
//             str += '<td>' + array[i][index] + '</td>';
//         }
//         str += '</tr>';
//     }
//     str += '</tbody>'
//     str += '</table>';
//     return str;
// }




$(document).ready(function () {
	var json = {"http:\/\/www.easyrdf.org\/doap.rdf#easyrdf":{"http:\/\/www.w3.org\/1999\/02\/22-rdf-syntax-ns#type":[{"type":"uri","value":"http:\/\/usefulinc.com\/ns\/doap#Project"}],"http:\/\/usefulinc.com\/ns\/doap#name":[{"type":"literal","value":"EasyRDF"}],"http:\/\/usefulinc.com\/ns\/doap#shortname":[{"type":"literal","value":"easyrdf"}],"http:\/\/usefulinc.com\/ns\/doap#revision":[{"type":"literal","value":"0.9.0"}],"http:\/\/usefulinc.com\/ns\/doap#shortdesc":[{"type":"literal","value":"EasyRdf is a PHP library designed to make it easy to consume and produce RDF.","lang":"en"}],"http:\/\/usefulinc.com\/ns\/doap#homepage":[{"type":"uri","value":"http:\/\/www.easyrdf.org\/"}],"http:\/\/usefulinc.com\/ns\/doap#programming-language":[{"type":"literal","value":"PHP"}],"http:\/\/usefulinc.com\/ns\/doap#description":[{"type":"literal","value":"EasyRdf is a PHP library designed to make it easy to consume and produce RDF. It was designed for use in mixed teams of experienced and inexperienced RDF developers. It is written in Object Oriented PHP and has been tested extensively using PHPUnit.","lang":"en"}],"http:\/\/usefulinc.com\/ns\/doap#license":[{"type":"uri","value":"http:\/\/usefulinc.com\/doap\/licenses\/bsd"}],"http:\/\/usefulinc.com\/ns\/doap#download-page":[{"type":"uri","value":"http:\/\/github.com\/njh\/easyrdf\/downloads"}],"http:\/\/usefulinc.com\/ns\/doap#bug-database":[{"type":"uri","value":"http:\/\/github.com\/njh\/easyrdf\/issues"}],"http:\/\/usefulinc.com\/ns\/doap#mailing-list":[{"type":"uri","value":"http:\/\/groups.google.com\/group\/easyrdf"}],"http:\/\/usefulinc.com\/ns\/doap#category":[{"type":"uri","value":"http:\/\/dbpedia.org\/resource\/Resource_Description_Framework"},{"type":"uri","value":"http:\/\/dbpedia.org\/resource\/PHP"},{"type":"uri","value":"http:\/\/www.dbpedialite.org\/things\/24131#id"},{"type":"uri","value":"http:\/\/www.dbpedialite.org\/things\/53847#id"}],"http:\/\/usefulinc.com\/ns\/doap#repository":[{"type":"bnode","value":"_:genid1"}],"http:\/\/usefulinc.com\/ns\/doap#maintainer":[{"type":"uri","value":"http:\/\/njh.me\/"}],"http:\/\/usefulinc.com\/ns\/doap#developer":[{"type":"uri","value":"http:\/\/njh.me\/"}],"http:\/\/xmlns.com\/foaf\/0.1\/maker":[{"type":"uri","value":"http:\/\/njh.me\/"}]},"_:genid1":{"http:\/\/www.w3.org\/1999\/02\/22-rdf-syntax-ns#type":[{"type":"uri","value":"http:\/\/usefulinc.com\/ns\/doap#GitRepository"}],"http:\/\/usefulinc.com\/ns\/doap#browse":[{"type":"uri","value":"http:\/\/github.com\/njh\/easyrdf"}],"http:\/\/usefulinc.com\/ns\/doap#location":[{"type":"uri","value":"git:\/\/github.com\/njh\/easyrdf.git"}]},"http:\/\/njh.me\/":{"http:\/\/www.w3.org\/1999\/02\/22-rdf-syntax-ns#type":[{"type":"uri","value":"http:\/\/xmlns.com\/foaf\/0.1\/Person"}],"http:\/\/xmlns.com\/foaf\/0.1\/name":[{"type":"literal","value":"Nicholas J Humfrey"}],"http:\/\/xmlns.com\/foaf\/0.1\/homepage":[{"type":"uri","value":"http:\/\/www.aelius.com\/njh\/"}]}};
    /*var json = {
        "hof_Details": {
            "CATEGORY_DESC_ENG": "SC",
            "AADHAR_ID": "780623649996",
            "STATE": "Rajasthan",
            "MOTHER_NAME_ENG": "hurami ",
            "BLOCK_CITY": "Shergarh"
        },
        "family_Details": [
           {
               "CATEGORY_DESC_ENG": "SC",
               "STATE": "Rajasthan",
               "MOTHER_NAME_ENG": "khetu devi ",
           },
           {
               "CATEGORY_DESC_ENG": "SC",
               "STATE": "Rajasthan",
               "MOTHER_NAME_ENG": "SEETA ",
           }
        ]
    };

 */
    $('#results').html(CreateDetailView(json));
   // $('#table').attr("style","border:10px;cell-padding:0px;cell-spacing:0px");
});
    
   // alert("jhgkjtr");
</script>
</html>