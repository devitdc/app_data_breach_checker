/****************************
 Used to show form when user click on tab
*/
function openTab(evt, checkerName) {
    let i, tabcontent, tablinks
    tabcontent = document.getElementsByClassName("tabcontent")

    for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none"
    }
    tablinks = document.getElementsByClassName("tablinks")

    for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "")
    }

    document.getElementById(checkerName).style.display = "block"
    evt.currentTarget.className += " active"
}
/***************************** */


/******************************
Used to open the right menu based on the URL path
*/
const url = new URL(window.location)

if (url.pathname === '/scan/email') {
    document.getElementById("scanEmail").click()
    document.getElementById("scanEmail").scrollIntoView({behavior : "smooth"})
} else if (url.pathname === '/scan/password') {
    document.getElementById("scanPassword").click()
    document.getElementById("scanPassword").scrollIntoView({behavior : "smooth"})
}
/***************************** */


/*****************************
 Used to display the rest of the text
 */
function readMore() {
    const text = document.querySelector('.info')

    if (text.classList.contains("show-read-more")) {
        text.classList.remove("show-read-more")
        text.classList.add("hide-read-more")
    }else {
        text.classList.add("show-read-more")
        text.classList.remove("hide-read-more")
    }
}
/***************************** */


/*****************************
 Used to display modal when screen size is less than 700px
 */
if (window.matchMedia("(max-width: 700px)").matches) {
    const mobileScreen = sessionStorage.getItem("mobileScreen")

    if (mobileScreen === null) {
        sessionStorage.setItem("mobileScreen", "1")
        document.getElementById('btnModalInfoScreen').click()
    }
}
/***************************** */

