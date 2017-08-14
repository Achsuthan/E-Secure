/*
	By Osvaldas Valutis, www.osvaldas.info
	Available for use under the MIT License
*/

if (sessionStorage.user =="" || sessionStorage.user==null)
{
    window.location.href = "../Login/index.html";
}
else {


    function getfile() {
        /* var x = document.getElementById("file");
         var txt = "";
         if ('files' in x) {
         if (x.files.length == 0) {
         txt = "Select one or more files.";
         } else {
         for (var i = 0; i < x.files.length; i++) {
         txt += "<br><strong>" + (i+1) + ". file</strong><br>";
         var file = x.files[i];
         if ('name' in file) {
         txt += "name: " + file.name + "<br>";
         }
         if ('size' in file) {
         txt += "size: " + file.size + " bytes <br>";
         }
         }
         }
         }
         else {
         if (x.value == "") {
         txt += "Select one or more files.";
         } else {
         txt += "The files property is not supported by your browser!";
         txt  += "<br>The path of the selected file: " + x.value; // If the browser does not support the files property, it will return the path of the selected file instead.
         }
         }
         document.getElementById("demo").innerHTML = txt;*/

        var value = document.getElementById("file").value;

        if (value != "") {

            var data = new FormData();
            data.append("id", sessionStorage.uid);
            data.append("person",sessionStorage.person);
            data.append("file", document.querySelector("#file").files[0]);

            alert("File Uploaded Successfully wait for the Result, Result will be come very soon \n Important \n 1. Don't Upload any file again up to result will come \n 2. Don't refresh the page result won't display");

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                     //alert(this.responseText);
                    getfiledetails(this.responseText);

                }
            };
            xmlhttp.open("POST", "http://192.168.172.140/SLIIT/E-Sucure/getfile.php");
            xmlhttp.send(data);
        }
        else {
            alert("First Select a file to upload");
        }
    }

    function checkfile() {

    }


    function getfiledetails(output) {

        timer = setInterval(function () {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    if (this.responseText != "") {
                        clearInterval(timer);

			//alert(this.responseText);
                        array = JSON.parse(this.responseText);

                        var table = document.getElementById("myTable");
                        var row = table.insertRow(0);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        cell1.innerHTML = "Id";
                        cell2.innerHTML = array.id;

                        var row = table.insertRow(1);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        cell1.innerHTML = "Name";
                        cell2.innerHTML = array.name;


                        var row = table.insertRow(2);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        cell1.innerHTML = "Type";
                        cell2.innerHTML = array.type;

                        var row = table.insertRow(3);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        cell1.innerHTML = "Size";
                        cell2.innerHTML = array.size+" Bytes";

                        var row = table.insertRow(4);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);

                        cell1.innerHTML = "Start time ";
                        cell2.innerHTML = array.started + " Seconds";

                        var row = table.insertRow(5);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);

                        cell1.innerHTML = "End time ";
                        cell2.innerHTML = array.ended + " Seconds";


                        var row = table.insertRow(5);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);

                        cell1.innerHTML = "Duration ";
                        cell2.innerHTML = array.duration + " Seconds";


                        var row = table.insertRow(7);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);

                        cell1.innerHTML = "SHA1 ";
                        cell2.innerHTML = array.sha1;


                        var row = table.insertRow(8);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);

                        cell1.innerHTML = "SHA256 ";
                        cell2.innerHTML = array.sha256;

                        var row = table.insertRow(9);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);

                        cell1.innerHTML = "MD5 ";
                        cell2.innerHTML = array.md5;

                        var row = table.insertRow(10);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);

                        cell1.innerHTML = "Malware Type";
                        cell2.innerHTML = "{malware}";

                        var row = table.insertRow(11);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);

                        cell1.innerHTML = "Critical ";
                        cell2.innerHTML = "{Critical}";
                    }
                }
            };
            xmlhttp.open("GET", "http://192.168.172.140/SLIIT/E-Sucure/getfiledetails.php?filename=" + output);
            xmlhttp.send();
        }, 5000);
    }
}




