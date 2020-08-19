'use strict';

angular.module('templateEditor.controllers', [])
/**
 * Main Controller
 */
    .controller('TemplateEditorCtrl', function($scope, $timeout, $http, $sce, $compile, $modal, $filter) {
        /**
         * Alerts
         */
        $scope.alerts = [
            //{ type: 'info', msg: 'Hi there!' },
        ];

        $scope.closeAlert = function(index) {
            $scope.alerts.splice(index, 1);
        };

        /**
         * Options
         */
        $scope.shortenTextOptions = [{
            id: 'none', name: 'Do not shorten text' },{
            id: 'characters', name: 'Limit to X characters' },{
            id: 'words', name: 'Limit to X words' }
        ];

        $scope.floatOptions = [{
            id: 'none', name: 'None' },{
            id: 'left', name: 'Left' },{
            id: 'right', name: 'Right' }
        ];

        $scope.positionOptions = [{
            id: 'static', name: 'Static' },{
            id: 'absolute', name: 'Absolute' },{
            id: 'relative', name: 'Relative' },{
            id: 'Fixed', name: 'Fixed' }
        ];

        $scope.dateFormatOptions = [{
            id: 'm/d/Y', name: '01/28/2014' },{
            id: 'm/d/y', name: '01/28/14' },{
            id: 'd/m/Y', name: '28/01/2014' },{
            id: 'd/m/y', name: '28/01/14' },{
            id: 'Y-m-d', name: '2014-01-28' }
        ];

        $scope.imagePresetOptions = [{
            id: 'missing-image', name: 'Missing Exercise Image' }
        ];

        $scope.imagePresetDefaults = function(block) {
            switch (block['imagePreset']) {
                case 'missing-image':
                    block['width'] = 150;
                    block['height'] = 150;
                    break;
            }
        }

        $scope.backgroundPresetOptions = [{
            id: 'default', name: 'Default' },{
            id: 'default-blue', name: 'Default (Blue)' },{
            id: 'default-brown', name: 'Default (Brown)' },{
            id: 'default-green', name: 'Default (Green)' }
        ];

        $scope.borderStyleOptions = [{
            id: 'solid', name: 'Solid Line' },{
            id: 'dashed', name: 'Dashed Line' },{
            id: 'dotted', name: 'Dotted Line' },{
            id: 'double', name: 'Double Line' }
        ];

        $scope.shadowTypeOptions = [{
            id: '', name: 'Outer Shadow' },{
            id: 'inset',   name: 'Inner Shadow' }
        ];

        $scope.fontFamilyTypeOptions = [{
            id: '', name: 'Inherit' },{
            id: 'gwf', name: 'Google Web Fonts' },{
            id: 'manual',   name: 'Manual' }
        ];

        // Font options
        $.getJSON('../vendor/google_web_fonts.json', function(json) {
            $scope.fontFamilyGWFOptions = $.map(json.items, function(font, index) {
                var id = font.family.replace(' ','+')
                return [{id: id, name: font.family}]
            })
        });

        $scope.columnsRowsOptions = [{
            id: 1, name: '1' },{
            id: 2, name: '2' },{
            id: 3, name: '3' },{
            id: 4, name: '4' },{
            id: 5, name: '5' },{
            id: 6, name: '6' },{
            id: 7, name: '7' },{
            id: 8, name: '8' },{
            id: 9, name: '9' },{
            id: 10, name: '10' }
        ];

        $scope.titleTagOptions = [{
            id: 'span', name: 'Normal text' },{
            id: 'div', name: 'Block text' },{
            id: 'h1', name: 'Heading 1' },{
            id: 'h2', name: 'Heading 2' },{
            id: 'h3', name: 'Heading 3' },{
            id: 'h4', name: 'Heading 4' },{
            id: 'h5', name: 'Heading 5' },{
            id: 'h6', name: 'Heading 6' }
        ];

        $scope.textAlignOptions = [{
            id: 'inherit', name: 'Inherit' },{
            id: 'left', name: 'Left' },{
            id: 'center', name: 'Center' },{
            id: 'right', name: 'Right' }
        ];

        $scope.verticalAlignOptions = [{
            id: 'inherit', name: 'Inherit' },{
            id: 'baseline', name: 'Baseline' },{
            id: 'sub', name: 'Sub' },{
            id: 'super', name: 'Super' },{
            id: 'text-top', name: 'Text Top' },{
            id: 'text-bottom', name: 'Text Bottom' },{
            id: 'middle', name: 'Middle' },{
            id: 'top', name: 'Top' },{
            id: 'bottom', name: 'Bottom' }
        ];

        $scope.showGroupsOptions = [{
            id: 'all', name: 'Show all groups' },{
            id: 'only', name: 'Show only these groups:' },{
            id: 'except', name: 'Show all except these groups:' }
        ];

        $scope.listStyleOptions = [{
            id: 'none', name: 'None' },{
            id: 'circle', name: 'Circle' },{
            id: 'disc', name: 'Disc' },{
            id: 'square', name: 'Square' },{
            id: 'decimal', name: 'Decimal' },{
            id: 'decimal-leading-zero', name: 'Decimal with leading zero' },{
            id: 'lower-roman', name: 'Lower Roman' },{
            id: 'upper-roman', name: 'Upper Roman' },{
            id: 'lower-latin', name: 'Lower Latin' },{
            id: 'upper-latin', name: 'Upper Latin' },{
            id: 'lower-greek', name: 'Lower Greek' },{
            id: 'armenian', name: 'Armenian' },{
            id: 'georgian', name: 'Georgian' }
        ];

        $scope.twitterLayoutOptions = [{
            id: 'none', name: 'No count' },{
            id: 'horizontal', name: 'Horizontal count' },{
            id: 'vertical', name: 'Vertical count' }
        ];

        $scope.facebookLayoutOptions = [{
            id: 'button', name: 'No count' },{
            id: 'button_count', name: 'Horizontal count' },{
            id: 'box_count', name: 'Vertical count' }
        ];

        $scope.googleLayoutOptions = [{
            id: 'medium', name: 'No count' },{
            id: 'medium_bubble', name: 'Horizontal count' },{
            id: 'tall', name: 'Vertical count' }
        ];

        $scope.pinterestLayoutOptions = [{
            id: 'none', name: 'No count' },{
            id: 'above', name: 'Vertical count' }
        ];

        $scope.stumbleuponLayoutOptions = [{
            id: '4', name: 'No count' },{
            id: '1', name: 'Horizontal count' },{
            id: '5', name: 'Vertical count' }
        ];

        $scope.linkedinLayoutOptions = [{
            id: '', name: 'No count' },{
            id: 'right', name: 'Horizontal count' },{
            id: 'top', name: 'Vertical count' }
        ];

        $scope.templates = []

         var loadTemplate = function(template) {
            var data = {
                template_id: template.id
            }

            $http.post('importer.php', data)
                .success(function(objData, status) {
                    $timeout(function() { // Fixes $rootScope inprog error
                        // Object to array
                        var data = []
                        for(var i in objData) {
                            data[i] = objData[i]
                        }

                        // Set values
                        $scope.template.blocks = data

                        // Clear container
                        jQuery('#0').empty()
                        $scope.template.blocks[0] = $scope.blockDefaults($scope.template.blocks[0])

                        $scope.currentOrder = data.length

                        // TODO Clean this up
                        var addToCanvas = function(inCanvas, data, id) {
                            if(jQuery.inArray(id, inCanvas) == -1) {
                                var parent_id = parseInt(data[id]['parent'])

                                if(parent_id != -1 && jQuery.inArray(parent_id, inCanvas) == -1) {
                                    inCanvas = addToCanvas(inCanvas, data, parent_id)
                                }

                                var blocktype = data[id]['type']

                                if(blocktype != 'container') {
                                    var droppableBlockTypes = ['rows', 'columns', 'table', 'box']
                                    var block = '';
                                    var parent = '';

                                    if(droppableBlockTypes.indexOf(blocktype) > -1) {
                                        block = $compile('<table block id="'+id+'" class="block '+blocktype+'" ng-class="{editing:editingBlock=='+id+'}" ng-hide="template.blocks['+id+'][\'deleted\']" data-order="'+ data[id]['order'] +'" movable><tr><td id="'+id+'-0-0" class="dropzone" data-parent="'+id+'" data-row="0" data-col="0" droppable>')($scope)
                                    } else { // Non droppable
                                        block = $compile('<div block id="'+id+'" class="block '+blocktype+'" ng-class="{editing:editingBlock=='+id+'}" ng-hide="template.blocks['+id+'][\'deleted\']" data-order="'+ data[id]['order'] +'" movable>')($scope)
                                    }

                                    // Parent is droppable
                                    if(droppableBlockTypes.indexOf(data[data[id]['parent']]['type']) > -1) {
                                        parent = '#' + parent_id + '-' + data[id]['row'] + '-' + data[id]['column'];
                                    } else {
                                        parent = '#' + parent_id;
                                    }

                                    // Add block to parent
                                    jQuery(parent).append(block);

                                    // Sort children by order attribute
                                    jQuery(parent).children('.block').sort(function(a,b) {
                                        return +a.getAttribute('data-order') - +b.getAttribute('data-order')
                                    }).appendTo(parent);

                                    // Block Defaults
                                    $scope.template.blocks[id] = $scope.blockDefaults($scope.template.blocks[id])

                                    // Scope order
                                    if($scope.currentOrder <= data[id]['order']) $scope.currentOrder = data[id]['order']+1

                                    $scope.$apply()
                                    inCanvas.push(id)
                                    return inCanvas
                                }
                            }

                            return inCanvas
                        }

                        var inCanvas = []

                        for(var id in data)
                        {
                            inCanvas = addToCanvas(inCanvas, data, parseInt(id))
                        }

                        $scope.template.id = template.id
                        $scope.template.name = template.name

                        $scope.debug['json-status'] = status;
                    }, 0);
                })
                .error(function(data, status) {
                    $scope.debug['json'] = data || "Request failed";
                    $scope.debug['json-status'] = status;
                })
            ;
        }

        $http.post('manager.php', [])
            .success(function(data, status) {
                $scope.templates = data
            })
            .error(function(data, status) {
                $scope.debug['json'] = data || "Request failed";
                $scope.debug['json-status'] = status;
            })
        ;

        $scope.openLoadTemplateModal = function () {
            var modalInstance = $modal.open({
                templateUrl: 'loadTemplateModal.html',
                controller: 'ModalLoadTemplateCtrl',
                backdrop: 'static',
                resolve: {
                    templates: function () {
                        return $scope.templates;
                    }
                }
            });

            modalInstance.result.then(function (data) {
                if(data !== undefined) {
                    if(data.action == 'load') {
                        loadTemplate(data.template)
                    }
                    if(data.action == 'delete') {
                        var data = {
                            template: data.template.id
                        }

                        $http.post('manager.php', data)
                            .success(function(data, status) {
                                $scope.templates = data
                                $scope.alerts.push({ type: 'success', msg: 'The template has been deleted.' })
                            });
                    }
                }
            }, function() {});
        };

        $scope.openSaveTemplateModal = function () {
            var modalInstance = $modal.open({
                templateUrl: 'saveTemplateModal.html',
                controller: 'ModalSaveTemplateCtrl',
                backdrop: 'static',
                resolve: {
                    template: function () {
                        return $scope.template;
                    }
                }
            });

            modalInstance.result.then(function (data) {
                if(data !== undefined) {
                    data.template = $scope.template

                    $http.post('parser.php', data)
                        .success(function(data, status) {
                            if(data !== null && typeof data === 'object') {
                                $scope.templates.push(data)

                                $scope.template.id = data.id
                                $scope.template.name = data.name
                            }
                        })
                }
            }, function() {});
        };

        $scope.openImportTemplateModal = function () {
            var modalInstance = $modal.open({
                templateUrl: 'importTemplateModal.html',
                controller: 'ModalImportTemplateCtrl',
                backdrop: 'static'
            });

            modalInstance.result.then(function (data) {
                if(data !== undefined) {
                    data.template = {
                        id: null,
                        blocks: []
                    }

                    data.saveAsNew = true

                    $http.post('parser.php', data)
                        .success(function(data, status) {
                            if(data !== null && typeof data === 'object' && !data.error) {
                                $scope.templates.push(data)
                                loadTemplate(data)
                            } else {
                                $scope.alerts.push({ type: 'danger', msg: 'The template was not correctly imported.' })
                            }
                        })
                }
            }, function() {});
        };

        $scope.openExportTemplatePage = function () {
            var data = {
                template: $scope.template,
                export: true
            }

            $http.post('parser.php', data)
                .success(function(data, status) {
                    document.getElementById('exportTemplateValue').value = data;
                    document.getElementById('exportTemplate').submit();
                })
            ;
        };

        $scope.openEditParagraphModal = function () {

            var modalInstance = $modal.open({
                templateUrl: 'editParagraphModal.html',
                controller: 'ModalParagraphCtrl',
                backdrop: 'static',
                resolve: {
                    paragraph: function () {
                        return $scope.template.blocks[$scope.editingBlock]['paragraph'];
                    }
                }
            });

            modalInstance.result.then(function (paragraph) {
                $scope.template.blocks[$scope.editingBlock]['paragraph'] = paragraph
            }, function () {});
        };

        $scope.openEditConditionsModal = function () {

            var modalInstance = $modal.open({
                templateUrl: 'editConditionsModal.html',
                controller: 'ModalConditionsCtrl',
                backdrop: 'static',
                resolve: {
                    block: function () {
              