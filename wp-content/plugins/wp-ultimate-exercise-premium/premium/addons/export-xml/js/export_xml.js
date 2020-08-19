var ExportXML = ExportXML || {};

ExportXML.selectAllExercies = function() {
    jQuery('.xml-exercise').each(function() {
        jQuery(this).attr('checked', true);
    });
};

ExportXML.deselectAllExercies = function() {
    jQuery('.xml-exercise').each(function() {
        jQuery(this).attr('checked', false);
    });
};