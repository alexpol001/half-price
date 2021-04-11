import 'admin-lte/plugins/datatables/jquery.dataTables';
import 'admin-lte/plugins/datatables/dataTables.bootstrap4';
import 'admin-lte/plugins/select2/js/select2.min';
import 'admin-lte/plugins/inputmask/jquery.inputmask.bundle';
import 'admin-lte/plugins/daterangepicker/daterangepicker';
import 'admin-lte/plugins/summernote/summernote-bs4';

$(":input").inputmask();

$('textarea.adminlte-editor').summernote();

(function($) {

    var Defaults = $.fn.select2.amd.require('select2/defaults');

    $.extend(Defaults.defaults, {
        searchInputPlaceholder: ''
    });

    var SearchDropdown = $.fn.select2.amd.require('select2/dropdown/search');

    var _renderSearchDropdown = SearchDropdown.prototype.render;

    SearchDropdown.prototype.render = function(decorated) {

        // invoke parent method
        var $rendered = _renderSearchDropdown.apply(this, Array.prototype.slice.apply(arguments));

        this.$search.attr('placeholder', this.options.get('searchInputPlaceholder'));

        return $rendered;
    };

})(window.jQuery);
