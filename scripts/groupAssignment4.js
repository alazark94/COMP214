window.addEventListener("load", () => {
    var date = new Date();
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();

    if (month < 10) month = "0" + month;
    if (day < 10) day = "0" + day;

    var today = year + "-" + month + "-" + day;

    document.getElementById("hireDate").value = today;
});

document.getElementById("job").addEventListener("change", () => {
    document.getElementById("jTitle").value =
        document.getElementById("job").options[
            document.getElementById("job").selectedIndex
        ].text;
    document.getElementById("jID").value = document.getElementById("job").value;
});

document.getElementById("managerDropdown").addEventListener("change", () => {
    document.getElementById("manID").value =
        document.getElementById("managerDropdown").value;
});

document.getElementById("dep").addEventListener("change", () => {
    document.getElementById("deptIDField").value =
        document.getElementById("dep").value;
});
