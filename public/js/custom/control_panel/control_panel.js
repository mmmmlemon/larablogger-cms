//scripts for Control Panel

//disables the red highlight on input change
$("input").change(function () {
    $(this).removeClass("is-danger")
});

//submit social media form
function submit_social_media() {
    //get num of field from #num_of_fields element
    var num_of_fields = $('#num_of_fields').html();
    //get the form
    var action = $("#form_social").attr("action");
    //change action attribute from the form, add num of field param to it
    //submit the form
    $("#form_social").attr("action", action + num_of_fields);
    $("#form_social").submit();
}

//switch settings tab
//(id of a div element and id of a tab)
function change_tab(div_name, tab_name) {
    //remove current div, add invisible class
    $(".current-content").removeClass("current-content").addClass("invisible");
    //view new div
    $("#" + div_name).removeClass("invisible").addClass("current-content fade-in");

    //add class is-active to the current tab
    $(".current-tab").removeClass("current-tab is-active");
    $("#" + tab_name).addClass("is-active current-tab");
}

//if url has an anchor, switch to the anchored tab
if (window.location.hash) {
    var hash = window.location.hash.substring(1);
    if (hash == 'settings') {
        $("#settings_tab").click();
    } else if (hash == 'design') {
        $("#design_tab").click();
    } else if (hash == 'users') {
        $("#users_tab").click();
    } else if (hash == 'profile') {
        $("#profile_tab").click();
    } else if (hash == 'posts') {
        $("#posts_tab").click();
    } else { /*do nothing*/}
}
