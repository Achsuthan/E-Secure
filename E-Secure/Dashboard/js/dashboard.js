/*
	By Osvaldas Valutis, www.osvaldas.info
	Available for use under the MIT License
*/

if (sessionStorage.user =="" || sessionStorage.user==null)
{
    window.location.href = "../Login/index.html";
}
else {

    /*
     var xmlhttp = new XMLHttpRequest();
     xmlhttp.onreadystatechange = function() {
     if (this.readyState == 4 && this.status == 200) {
     output = JSON.parse(this.responseText);

     if (output.status == "200") {
     var total = parseInt(output.virus) + parseInt(output.trojan) + parseInt(output.worms) + parseInt(output.adware) + parseInt(output.others);
     var virus = (parseInt(output.virus)) / total * 100;
     var trojan = (parseInt(output.trojan)) / total * 100;
     var worms = (parseInt(output.worms)) / total * 100;
     var adware = (parseInt(output.adware)) / total * 100;
     var others = (parseInt(output.others)) / total * 100;

     document.getElementById("virus").style.height = virus + "px";
     document.getElementById("pvirus").innerHTML = "Virus " + Math.round(virus) + "%";
     document.getElementById("worms").style.height = worms + "px";
     document.getElementById("pworms").innerHTML = "Worms " + Math.round(worms) + "%";
     document.getElementById("trojan").style.height = trojan + "px";
     document.getElementById("ptrojan").innerHTML = "Trojan " + Math.round(trojan) + "%";
     document.getElementById("adware").style.height = adware + "px";
     document.getElementById("padware").innerHTML = "Adware " + Math.round(adware) + "%";
     document.getElementById("others").style.height = others + "px";
     document.getElementById("pothers").innerHTML = "Others " + Math.round(others) + "%";


     }
     else if (output.status == "400") {
     alert("Something went wrong in the training session please try again later");
     }
     else {
     alert("System Error : Try again later")
     }
     }
     };
     xmlhttp.open("GET", "http://192.168.172.140/SLIIT/E-Secure/train_details.php");
     xmlhttp.send();




     */

    document.getElementById("virus").style.height = "0px";
    document.getElementById("pvirus").innerHTML = "Virus 0%";
    document.getElementById("worms").style.height = "0px";
    document.getElementById("pworms").innerHTML = "Worms 0%";
    document.getElementById("trojan").style.height = "0px";
    document.getElementById("ptrojan").innerHTML = "Trojan 0%";
    document.getElementById("adware").style.height = "0px";
    document.getElementById("padware").innerHTML = "Adware 0%";
    document.getElementById("others").style.height = "0px";
    document.getElementById("pothers").innerHTML = "Others 0%";


    function train() {
        alert("Training Session Started, Please wait for the Results \n Important \n 1. Don't close the browser \n 2. Don't refresh the page training session will be destroyed ");
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                output = JSON.parse(this.responseText);

                if (output.status == "200") {
                    alert("Training session completed You can now start the system for \n analyse the mail \n attachment \n URLs");
                    var total = output.virus + output.trojan + output.worms + output.adware + output.others;
                    var virus = (output.virus) / total * 100;
                    var trojan = (output.trojan) / total * 100;
                    var worms = (output.worms) / total * 100;
                    var adware = (output.adware) / total * 100;
                    var others = (output.others) / total * 100;

                    document.getElementById("virus").style.height = virus + "px";
                    document.getElementById("pvirus").innerHTML = "Virus " + Math.round(virus) + "%";
                    document.getElementById("worms").style.height = worms + "px";
                    document.getElementById("pworms").innerHTML = "Worms " + Math.round(worms) + "%";
                    document.getElementById("trojan").style.height = trojan + "px";
                    document.getElementById("ptrojan").innerHTML = "Trojan " + Math.round(trojan) + "%";
                    document.getElementById("adware").style.height = adware + "px";
                    document.getElementById("padware").innerHTML = "Adware " + Math.round(adware) + "%";
                    document.getElementById("others").style.height = others + "px";
                    document.getElementById("pothers").innerHTML = "Others " + Math.round(others) + "%";


                }
                else if (output.status == "400") {
                    alert("Something went wrong in the training session please try again later");
                }
                else {
                    alert("System Error : Try again later")
                }

            }
        };
        xmlhttp.open("GET", "http://192.168.172.140/SLIIT/E-Secure/Training/training.php");
        xmlhttp.send();

    }

    function view_train_details()
    {
      window.open('http://192.168.172.140/E-Secure/TrainDetails/train_output.php',"_blank");
    }
}
