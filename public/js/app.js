$(document).ready(function () {
    $('.nav-link.active .sub-menu').slideDown();
    // $("p").slideUp();

    $('#sidebar-menu .arrow').click(function () {
        $(this).parents('li').children('.sub-menu').slideToggle();
        $(this).toggleClass('fa-angle-right fa-angle-down');
    });

    $("input[name='checkall']").click(function () {
        var checked = $(this).is(':checked');
        $('.table-checkall tbody tr td input:checkbox').prop('checked', checked);
    });
});
var loadFile = function (output) {
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
        URL.revokeObjectURL(output.src) // free memory
    }
};
var loadFileLogo = function (event) {
    var output = document.getElementById('school_logo');
    loadFile(output);
};
var loadFileImage = function (event) {
    var output = document.getElementById('school_image');
    loadFile(output);
};



tinymce.init(
    {
        selector: 'textarea#school_description',
        menubar: false,
        toolbar: false,
        height: 380,
    }
);
tinymce.init(
    {
        selector: 'textarea#school_detail',
        plugins: "link image code table advtable lists checklist",
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent',
        height: 500,
    }
);
