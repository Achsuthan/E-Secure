/*
	By Osvaldas Valutis, www.osvaldas.info
	Available for use under the MIT License
*/


if (sessionStorage.user =="" || sessionStorage.user==null)
{
    window.location.href = "../Login/index.html";
}
else {

    function uploadurl() {
        if (document.getElementById("url").value != "") {
            var url = document.getElementById("url").value;

            if (validateURL(document.getElementById("url").value)) {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        alert("URL Uploaded Successfully wait for the Result, Result will be come very soon \n Important \n 1. Don't Upload Any URL again up to result will come \n 2. Don't refresh the page result won't display ");

                    }
                };
                xmlhttp.open("GET", "http://192.168.172.140/SLIIT/E-Secure/geturl.php?url=" + document.getElementById("url").value);
                xmlhttp.send();
            }
            else {
                alert("Enter valid URL");
            }
        }
        else {
            alert("Input the URL to Scann the Malware")
        }
    }


    function validateURL(textval) {
        var urlregex = /^(https?|ftp):\/\/([a-zA-Z0-9.-]+(:[a-zA-Z0-9.&%$-]+)*@)*((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}|([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(:[0-9]+)*(\/($|[a-zA-Z0-9.,?'\\+&%$#=~_-]+))*$/;
        return urlregex.test(textval);
    }

    function getfiledetails() {


        timer = setInterval(function () {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    if (this.responseText != "") {
                        document.getElementById("serverData").innerHTML = this.responseText;
                        return;
                    }
                }
            };
            xmlhttp.open("GET", "http://192.168.172.140/SLIIT/E-Secure/getfiledetails.php");
            xmlhttp.send();
        }, 5000);
    }
}
