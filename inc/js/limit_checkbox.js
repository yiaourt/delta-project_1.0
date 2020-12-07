var cb = document.querySelectorAll("[type=checkbox]");

var i = 0,

      l = cb.length;

for (; i<l; i++)

    cb[i].addEventListener("change", function (){
        if (document.querySelectorAll(":checked").length > 3)
            this.checked = false;
    }, false);