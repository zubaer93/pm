var a = document.querySelectorAll('.custom-tabs-container.mobile .dropdown-toggle');
for (var i in a) {
    a[i].onclick = function (e) {
        e.preventDefault();
        this.parentElement.classList.toggle('show');
    }
}