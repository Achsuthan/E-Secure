/*
	By Osvaldas Valutis, www.osvaldas.info
	Available for use under the MIT License
*/

if (sessionStorage.user =="" || sessionStorage.user==null)
{
    window.location.href = "../Login/index.html";
}
else {

    if (sessionStorage.user == "" || sessionStorage.user == null) {
        window.location.href = "../Login/index.html";
    }
    else {
        var value = "";
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText != "") {
                    // console.log(this.responseText);
                    document.getElementById("mytable").innerHTML = this.responseText;
                }
            }
        };
        xmlhttp.open("GET", "http://192.168.172.140/SLIIT/E-Secure/get_user_details.php");
        xmlhttp.send();
    }


    function file(id) {
        window.open('http://192.168.172.140/E-Secure/Output/csv_output.php?output=' + id,"_blank");
    }
}




