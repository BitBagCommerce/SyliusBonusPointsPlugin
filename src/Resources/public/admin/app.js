$(document).ready(() => {
    $('#rules a[data-form-collection="add"]').on('click', () => {
        setTimeout(() => {
            $('select[name^="bonus_points_strategy[rules]"][name$="[type]"]').last().change();
        }, 50);
    });
});
