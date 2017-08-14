
if (sessionStorage.user =="" || sessionStorage.user==null)
{
    window.location.href = "../Login/index.html";
}
else {


    if (sessionStorage.person=="Admin")
    {
        document.getElementById('dashboard').style.display="block";
        document.getElementById("userview").style.display="block";
    }
    else
    {
        document.getElementById("dashboard").style.display="none";
        document.getElementById("userview").style.display="none";
    }
}
