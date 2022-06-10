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
<!--<link rel="manifest" href="/favicons/site.webmanifest?v=6">-->
<!-- Removed because it gave an error and is only supported on some Android/Samsung devices-->
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

  .name {
      width: auto;
  }

  .namehov:hover {
      text-decoration: line-through;
      cursor: pointer;
      //border: 1px solid red;
  }

  .namedel {
      color: red;
      font-weight: bold;
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
    <button id="submit" onclick="addStudent(studentName.value)">Submit</button>
</div>
<div style="width: 400px; margin:0 auto;" id="reader"></div>

<span style="text-decoration: underline">List of Students: </span><span id="listOfStudentsText"></span>
<div id="namediv"></div>
<div id="deldiv"></div>

<div id="studentNameLoader">
  <button type="button" class="button" onclick="loadStudentNamesPost()">Force Load New Names</button>
</div>

<br>
<button type="button" class="button" onclick="clearInterval(intervalId)">Stop Loading</button>
<button type="button" class="button" onclick="window.speechSynthesis.cancel()">Fix TTS</button>
<button id="resetNames" type="button" class="button" onclick='resetStudents()'>Reset Names</button>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="sha256.min.js"></script>

<script src="html5-qrcode.min.js" type="text/javascript"></script>

<script>
var intervalId;

var password;
var permissions;
var salt = "tMr3XAzrSYF/229xhglYQgSPxVE8FFYr";
function enterPassword() {
    do {
        password = prompt("Enter password: ");
    } while (password == null || password == "" );
    password = password + salt;
    password = sha256(password);
    $.post( "./enterPassword.php", { password: password })
    .done(function( data ) {
        checkPassword(data);
    });
    /*var passwordReturn = jQuery.ajax('./enterPassword.php?password=' + password);
    passwordReturn.then(() => checkPassword());*/
    var passwordCorrect;

    function checkPassword(data) {
        passwordCorrect = data.includes("1") || data.includes("2");
        if (data.includes("1")) {
            permissions = 1;
            let removeElement;
            removeElement = document.getElementById('enterName');
            removeElement.parentElement.removeChild(removeElement);
            removeElement = document.getElementById('resetNames');
            removeElement.parentElement.removeChild(removeElement);
            removeElement = document.getElementById('reader');
            removeElement.parentElement.removeChild(removeElement);
        } else if (data.includes("2")) {
            permissions = 2;
            var elem = document.getElementById("studentName");
            elem.onkeyup = function(e){
                if(e.keyCode == 13){
                    addStudent(studentName.value);
                }
            }
        }
        if (!passwordCorrect) {
            document.body.parentNode.removeChild(document.body);
            document.head.parentNode.removeChild(document.head);
        } else {
            loadStudentNamesPost();
            intervalId = window.setInterval(function(){
                if (cancelNextLoad) {
                    cancelNextLoad = false;
                } else {
                    loadStudentNamesPost();
                }
            }, (intervalTime * 1000));
        }
    }
}
if (document.visibilityState != "hidden") {
    enterPassword();
} else {
    $(document).on('visibilitychange', function() {
        if (document.visibilityState != "hidden") {
            enterPassword();
            $(document).off('visibilitychange');
        }
    })
}

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
    //console.log(`Scan result: ${decodedText}`, decodedResult);
    document.getElementById("studentName").value = decodedText;
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
var deletedListOfStudents = new Array();
var intervalTime = 3;
var tts = false;
var cancelNextLoad = false;

function loadStudentNamesPost() {
    if (window.navigator.onLine) {
        $.post( "./getNames.php", { password: password })
        .done(function( data ) {
            let raw = data.replace("<!DOCTYPE html>\n<html>\n<head>\n    <meta http-equiv=\"Content-Security-Policy\" content=\"upgrade-insecure-requests\">\n</head>\n<body>\n\n", "").replace("\n</body>\n</html>", "");
            listOfStudents = JSON.parse(JSON.parse(raw)[0]);
            deletedListOfStudents = JSON.parse(JSON.parse(raw)[1]);
            updateNewestStudent();
            displayNames();
        });
    }
}
window.addEventListener('online', () => 
    window.setInterval(function(){
        if (cancelNextLoad) {
            cancelNextLoad = false;
        } else {
            loadStudentNamesPost();
        }
    }, (intervalTime * 1000))
);
window.addEventListener('offline', () => 
    clearInterval(intervalId)
);

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
    } else if (newestStudent != oldNewestStudent) {
        document.getElementById("newestStudent").innerHTML = newestStudent;
        oldNewestStudent = newestStudent;
        if (tts) {
            var msg = new SpeechSynthesisUtterance();
            msg.text = newestStudent;
            window.speechSynthesis.speak(msg);
        }
    }
}

function removeElementsByClass(className){
    const elements = document.getElementsByClassName(className);
    while(elements.length > 0){
        elements[0].parentNode.removeChild(elements[0]);
    }
}

function addStudent(name) {
    listOfStudents.push(name);
    displayNames();
    updateNewestStudent();
    document.getElementById("studentName").value = '';
    if (window.navigator.onLine) {
        $.post( "./addStudent.php", { name: name, password: password })
        .done(function( data ) {
            loadStudentNamesPost();
        });
    } else {
        alert("Offline.");
    }
}

function removeStudent(id) {
    document.getElementsByClassName('name')[id].remove();
    deletedListOfStudents.push(listOfStudents[id]);
    listOfStudents.splice(id, 1);
    displayNames();
    if (window.navigator.onLine) {
        $.post( "./removeStudent.php", { id: id, password: password })
        .done(function( data ) {
            loadStudentNamesPost();
        });
    } else {
        alert("Offline.");
    }
}

function resetStudents() {
    removeElementsByClass('name');
    removeElementsByClass('namdel');
    listOfStudents = [];
    deletedListOfStudents = [];
    displayNames();
    if (window.navigator.onLine) {
        $.post( "./resetStudents.php", { password: password })
        .done(function( data ) {
            loadStudentNamesPost();
        });
    } else {
        alert("Offline.");
    }
}


function arraysEqual(a, b) {
  if (a === b) return true;
  if (a == null || b == null) return false;
  if (a.length !== b.length) return false;

  // If you don't care about the order of the elements inside
  // the array, you should sort both arrays here.
  // Please note that calling sort on an array will modify that array.
  // you might want to clone your array first.

  for (var i = 0; i < a.length; ++i) {
    if (a[i] !== b[i]) return false;
  }
  return true;
}

var oldList1;
var oldList2;
function displayNames(nameList = listOfStudents, deletedList = deletedListOfStudents) {
    for (let i = 0; i < 2; i++) {
        let oldList;
        let list;
        if (i == 0) {
            oldList = oldList1;
            list = nameList;
        } else {
            oldList = oldList2;
            list = deletedList;
        }
        if (oldList == null || !(arraysEqual(oldList, list))) {
            if (i == 0) {
                oldList1 = [].concat(listOfStudents);
                div = document.getElementById("namediv");
            } else {
                oldList2 = [].concat(deletedListOfStudents);
                div = document.getElementById("deldiv");
            }
            while (div.firstChild) {
                div.removeChild(div.firstChild);
            }
            if (list.length > 0) {
                for (let j = 0; j < list.length; j++) {
                    let span = document.createElement("span");
                    span.innerHTML = list[j];
                    if (i == 0) {
                        span.classList.add("name");
                        if (permissions >= 2) {
                            span.classList.add("namehov");
                            span.setAttribute('onclick', "removeStudent([].map.call(document.getElementsByClassName('name'), elem => elem.textContent).findIndex(element => element == this.textContent));")
                            //span.setAttribute('onclick', "let elements = document.getElementsByClassName('name'); let data = [].map.call(elements, elem => elem.textContent); let index = data.findIndex(element => element == this.textContent); console.log(index);");
                            //span.setAttribute('onclick', "removeStudent(this.id.replace('name-', ''));");
                        }
                        //span.setAttribute('id', 'name-' + j);
                    } else {
                        span.classList.add("namedel");
                        span.setAttribute('id', 'delname-' + j);
                        // Maybe add a feature where it either permanently removes a name or re-adds a name?
                    }
                    div.appendChild(span);
                    if (i != list.length) { 
                        br = document.createElement("br");
                        div.appendChild(br);
                    }
                }
            }
        }
    }
}

</script>

</body>
</html>