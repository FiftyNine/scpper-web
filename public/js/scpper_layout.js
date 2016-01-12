/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

window.onload = function () {
    // Assign handler to a site selector in layout
    document.getElementById("current-site").onchange = 
        function () {
          this.form.submit();
        };
};
