WPUltimateEPostGrid = WPUltimateEPostGrid || {};

WPUltimateEPostGrid.initFilterDropdown = function(container) {	
    var grid_id = container.data('grid');	
    var dropdowns = container.find('.wpupg-efilter-dropdown-item');

    WPUltimateEPostGrid.grids[grid_id].multiselect_type = container.data('multiselect-type');

    dropdowns.each(function() {
        var taxonomy = jQuery(this).data('taxonomy');

        jQuery(this).select2wpupg({
            allowClear: true
        }).on('change', function() {
            var terms = jQuery(this).val();
            if(terms) {
                if(!jQuery.isArray(terms)) terms = [terms];

                WPUltimateEPostGrid.grids[grid_id].filters[taxonomy] = terms;
            } else {
                WPUltimateEPostGrid.grids[grid_id].filters[taxonomy] = [];
            }

            WPUltimateEPostGrid.filterEgrid(grid_id);
        });
    });
};

WPUltimateEPostGrid.updateFilterDropdown = function(container, taxonomy, terms) {
    container.find('#wpupg-efilter-dropdown-' + taxonomy).select2wpupg('val', terms);
};