<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title></title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script>
        var wpuep = window.opener.wpuep_print;

        document.title = wpuep.title;

        // Include CSS files
        document.write('<link rel="stylesheet" type="text/css" href="' + wpuep.coreUrl + '/css/layout_base.css">');
        if(wpuep.premiumUrl) {
            document.write('<link rel="stylesheet" type="text/css" href="' + wpuep.premiumUrl + '/addons/nutritional-information/css/nutrition-label.css">');
            document.write('<link rel="stylesheet" type="text/css" href="' + wpuep.premiumUrl + '/addons/user-ratings/css/user-ratings.css">');
        }
        document.write('<style>' + wpuep.custom_print_css + '</style>');

        jQuery(document).ready(function() {

            // Set RTL if opener was in RTL
            if(wpuep.rtl) {
                jQuery('html').attr('dir', 'rtl')
                    .find('body').addClass('rtl');
            }

            var wpuep_printed = false;

            function startChecking()
            {
                checkForAjax()
                setTimeout(function(){
                    checkForAjax();
                }, 50);
            }

            function checkForAjax() {
                wpuep = window.opener.wpuep_print;

                if(wpuep.template != '') {

                    var html = '';
                    if(wpuep.fonts) {
                        html += '<link rel="stylesheet" type="text/css" href="' + wpuep.fonts + '">';
                    }
                    html += wpuep.template;

                    jQuery('body').html(html);
                    adjustServings();

                    if( !wpuep_printed ) {
                        setTimeout(function() {
                            window.print();
                        }, 1000); // TODO Check if everything is actually loaded
                        wpuep_printed = true;
                    }
                } else {
                    setTimeout(function() {
                        checkForAjax();
                    }, 50);
                }
            }

            // TODO Refactor
            function adjustServings()
            {
                var old_servings = wpuep.servings_original;
                var new_servings = wpuep.servings_new;
                var old_system = wpuep.old_system;
                var new_system = wpuep.new_system;

                // Premium system
                if(new_system != undefined && window.opener.ExerciseUnitConversion != undefined)
                {
                    var ingredientList = jQuery('.wpuep-exercise-ingredients');

                    if(old_servings != new_servings) {
                        window.opener.ExerciseUnitConversion.adjustServings(ingredientList, old_servings, new_servings)
                        jQuery('.wpuep-exercise-servings').text(new_servings);
                    }

                    if(old_system != new_system) {
                        window.opener.ExerciseUnitConversion.updateIngredients(ingredientList, old_system, new_system);
                    }
                }
                // Free system
                else if( !isNaN(old_servings) && !isNaN(new_servings) && old_servings != new_servings)
                {
                    var amounts = jQuery('.wpuep-exercise-ingredient-quantity');
                    window.opener.wpuep_adjustable_servings.updateAmounts(amounts, old_servings, new_servings);
                    jQuery('.wpuep-exercise-servings').text(new_servings);
                }

            }

            startChecking();
        });
    </script>
</head>
<body>
</body>
</html>