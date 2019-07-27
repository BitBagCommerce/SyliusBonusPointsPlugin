

$(document).ready(() => {
    $('#rules a[data-form-collection="add"]').on('click', () => {
        setTimeout(() => {
            $('select[name^="bonus_points_strategy[rules]"][name$="[type]"]').last().change();
        }, 50);
    });

    $('#bonus_points_strategy_calculatorType').handlePrototypes({
        prototypePrefix: 'bonus_points_strategy_calculatorType_calculators',
        containerSelector: '.configuration',
    });
});
