<!DOCTYPE html>
<html lang="en">
<head>
<!--<link rel='shortcut icon' href='favicon.ico' type='image/x-icon' />
<link rel="stylesheet" href="https://keys.dismissrr.com/assets/shoelace-gumby.css">-->

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;500&display=swap" rel="stylesheet" media="print" onload="this.media='all'">

<title>Dismissrr - Keys School</title>
<meta charset="UTF-8">
<meta name="description" content="Keys School Dismissal System">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="ME">
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

<link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png?v=6">
<link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png?v=6">
<link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png?v=6">
<link rel="manifest" href="/favicons/site.webmanifest?v=6">
<link rel="mask-icon" href="/favicons/safari-pinned-tab.svg?v=6" color="#5bbad5">
<link rel="shortcut icon" href="/favicons/favicon.ico?v=6">
<meta name="msapplication-TileColor" content="#000000">
<meta name="msapplication-config" content="/favicons/browserconfig.xml?v=6">
<meta name="theme-color" content="#000000">
<!-- Favicon Credit: School by lastspark from NounProject.com -->

<style>
  body {
      background-color:white;
      background-image:url("/background.webp");
      // background from https://gallery.yopriceville.com/Free-Clipart-Pictures/School-Clipart/School_Background_with_Pencils#.YZhrM9DMKUk
      background-repeat:no-repeat;
      background-size: 100% 100%;
      background-size:cover;
      background-position: 0px 3000px;
      -webkit-background-size:cover;
      -moz-background-size:cover;
      -o-background-size:cover;
      width:100%;
      text-align:center;
      color:black;
      padding:100px 0;
      height:100%;
      font-family: 'Open Sans', sans-serif, Arial;
      font-weight:300;
  }
  .h1 {color: black;}
  .p {color: black;}
  //https://keys.dismissrr.com/assets/header.jpg - jpg of keys dismissrr background

  .button {
      background-color: #FFFFFF; // Green
      border: none;
      color: white;
      padding: 16px 32px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      margin: 4px 2px;
      transition-duration: 0.4s;
      cursor: pointer;
      background-color: white; 
      color: black; 
      border: 2px solid #4CAF50;
      border-radius: 6px;
  }

  /*.button:hover {
      background-color: #4CAF50;
      color: black;
      border-radius: 6px;
  }*/

</style>
</head>
<body>

<h1 style="font-size:60px; font-weight:bold;">Dismissrr 2.0</h1>
<p>Newest Student: <span id="newestStudent"> </span></p>

<div id="enterName">
    <p id="enterNameText">Enter Name: <input type="text" id="studentName" value ="" size=20>
    <button id="submit" onclick="submitName(studentName.value)">Submit</button>
</div>
<div style="width: 500px; margin:0 auto;" id="reader"></div>

<span style="text-decoration: underline">List of Students: </span><span id="listOfStudentsText"></span>

<div id="studentNameLoader">
  <button type="button" class="button" onclick="loadStudentNamesAjax()">Force Load New Names</button>
</div>

<br>
<button type="button" class="button" onclick="clearInterval(intervalId)">Stop Loading</button>
<button type="button" class="button" onclick="window.speechSynthesis.cancel()">Fix TTS</button>
<button id="resetNames" type="button" class="button" onclick='sendNameUpdates("[]")'>Reset Names</button>

<?php
    /*function loadNamesPHP() {
        $namesJSON = file_get_contents('./studentNames.json');
        return $namesJSON;
    }*/
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="sha256.min.js"></script>

<script src="html5-qrcode.min.js" type="text/javascript"></script>

<script>

var password;
var permissions;
var salt = "tMr3XAzrSYF/229xhglYQgSPxVE8FFYr";
function enterPassword() {
    do {
        password = prompt("Enter password: ");
    } while (password == null || password == "" );
    password = password + salt;
    password = sha256(password);
    var passwordReturn = jQuery.ajax('./enterPassword.php?password=' + password);
    passwordReturn.then(() => checkPassword());
    var passwordCorrect;

    function checkPassword() {
        passwordCorrect = passwordReturn.responseText.includes("1") || passwordReturn.responseText.includes("2");
        if (passwordReturn.responseText.includes("1")) {
            permissions = "User";
            let removeElement;
            removeElement = document.getElementById('enterName');
            removeElement.parentElement.removeChild(removeElement);
            removeElement = document.getElementById('resetNames');
            removeElement.parentElement.removeChild(removeElement);
            removeElement = document.getElementById('reader');
            removeElement.parentElement.removeChild(removeElement);
        } else if (passwordReturn.responseText.includes("2")) {
            permissions = "Admin";
            var elem = document.getElementById("studentName");
            elem.onkeyup = function(e){
                if(e.keyCode == 13){
                submitName(studentName.value);
                }
            }
        }
        if (!passwordCorrect) {
            document.body.parentNode.removeChild(document.body);
            document.head.parentNode.removeChild(document.head);
        } else {
            loadStudentNamesAjax();
            var intervalId = window.setInterval(function(){
                if (cancelNextLoad) {
                    cancelNextLoad = false;
                } else {
                    loadStudentNamesAjax();
                }
            }, (intervalTime * 1000));
        }
    }
}
enterPassword();

const ascii64 =
'./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
function random_salt(length) {
    var salt = '';
    for (var i=0; i<length; i++)
        salt += ascii64.charAt( Math.floor(64 * Math.random()) );
    return salt;
}

var html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", { fps: 10, qrbox: 250 });
function onScanSuccess(decodedText, decodedResult) {
    // Handle on success condition with the decoded text or result.
    console.log(`Scan result: ${decodedText}`, decodedResult);
    // ...
    //html5QrcodeScanner.clear();
    // ^ this will stop the scanner (video feed) and clear the scan area.
}
function onScanError(errorMessage) {
    // handle on error condition, with error message
}
html5QrcodeScanner.render(onScanSuccess, onScanError);

$.fn.regexMask = function(mask) {
    $(this).keypress(function (event) {
        if (!event.charCode) return true;
        var part1 = this.value.substring(0, this.selectionStart);
        var part2 = this.value.substring(this.selectionEnd, this.value.length);
        if (!mask.test(part1 + String.fromCharCode(event.charCode) + part2))
            return false;
    });
};

var mask = new RegExp('^[A-Za-z0-9 ]*$')
$("input").regexMask(mask)

var listOfStudents = new Array();
var intervalTime = 3;
var tts = false;
var cancelNextLoad = false;

/*function loadStudentNamesPHP() {
    listOfStudents = <//?php echo loadNamesPHP();?>;
    updateNewestStudent();
    displayNames();
}*/

/*function loadStudentNamesXML() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     var parsedResponse = JSON.parse(this.response);
     listOfStudents = parsedResponse;
     updateNewestStudent();
     displayNames();
    }
  }
  xhttp.open("GET", "studentNames.json", true);
  xhttp.send();
}*/

function loadStudentNamesAjax() {
    var namesReturn = jQuery.ajax('./getNames.php?password=' + password);
    namesReturn.then(() => namesLoaded());

    function namesLoaded() {
        let raw = namesReturn.responseText.replace("<!DOCTYPE html>\n<html>\n<head>\n    <meta http-equiv=\"Content-Security-Policy\" content=\"upgrade-insecure-requests\">\n</head>\n<body>\n\n", "").replace("\n</body>\n</html>", "");
        listOfStudents = JSON.parse(raw);
        updateNewestStudent();
        displayNames();
    }
}

/*function jQueryLoadStudentNames() {
    $.getJSON('studentNames.json', function(data) {
    listOfStudents = data;
    updateNewestStudent();
    displayNames();
    });
}*/

if ('speechSynthesis' in window) {
    tts = true;
}else{
    alert("Sorry, your browser doesn't support text to speech!");
}

var oldNewestStudent;
var newestStudent;
function updateNewestStudent() {
    newestStudent = listOfStudents[listOfStudents.length - 1];
    if (newestStudent == null) {
        document.getElementById("newestStudent").innerHTML = "";
        oldNewestStudent = newestStudent;
    } else {
        if (newestStudent !== oldNewestStudent) {
            document.getElementById("newestStudent").innerHTML = newestStudent;
            oldNewestStudent = newestStudent;
            if (tts) {
                var msg = new SpeechSynthesisUtterance();
                msg.text = newestStudent;
                window.speechSynthesis.speak(msg);
            }
        }
    }
}

function submitName(newName) {
    document.getElementById("studentName").value = '';
    loadStudentNamesAjax();
    listOfStudents.push(newName);
    cancelNextLoad = true;
    modifyNames(listOfStudents);
}

function modifyNames() {
  var text = "[";
  for(let i = 0; i < listOfStudents.length; i++) {
      text += "\"";
      text += listOfStudents[i]; 
      text += "\"";
      if (i !== listOfStudents.length - 1) {
          text += ", ";
      }
  }
  text += "]";
  sendNameUpdates(text);
}

function sendNameUpdates(formattedList) {
  const xhttp = new XMLHttpRequest();
  xhttp.open("GET", "editStudentNames.php?list="+formattedList+"?password="+password);
  xhttp.onload = function() {
    //cancelNextLoad = true;
    //loadStudentNamesAjax();
  }
  xhttp.send();
}

function displayNames() {
    let text = "";
    text += "<br>";
    if (listOfStudents.length > 0) {
        for (let i = 0; i < listOfStudents.length; i++) {
            text += listOfStudents[listOfStudents.length - i - 1] + "<br>";
        }
    }
    document.getElementById("listOfStudentsText").innerHTML = text;
}

</script>

</body>
</html>
