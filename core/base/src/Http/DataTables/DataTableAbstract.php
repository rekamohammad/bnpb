<?php

namespace Botble\Base\Http\DataTables;

use Assets;
use Yajra\Datatables\Services\DataTable;

abstract class DataTableAbstract extends DataTable
{

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     * @author Sang Nguyen
     * @since 2.1
     */
    public function html()
    {
        Assets::addJavascript(['datatables']);
        Assets::addStylesheets(['datatables']);
        Assets::addAppModule(['datatables']);

        return $this->builder()
            ->columns($this->getColumns())
            ->parameters([
                'dom' => "Brt<'datatables__info_wrap'pli<'clearfix'>>",
                'buttons' => $this->getBuilderParameters(),
                'initComplete' => $this->htmlInitComplete(),
                'drawCallback' => $this->htmlDrawCallback(),
                'paging' => true,
                'searching' => true,
                'info' => true,
                'searchDelay' => 350,
                'bStateSave' => true,
                'lengthMenu' => [
                    [10, 30, 50, -1],
                    [10, 30, 50, trans('bases::tables.all')]
                ],
                'pageLength' => 10,
                'processing' => true,
                'serverSide' => true,
                'bServerSide' => true,
                'bDeferRender' => true,
                'bProcessing' => true,
                'language' => [
                    'aria' => [
                        'sortAscending' => 'orderby asc',
                        'sortDescending' => 'orderby desc'
                    ],
                    'emptyTable' => trans('bases::tables.no_data'),
                    'info' => '<span class="dt-length-records"><i class="fa fa-globe"></i> <span class="hidden-xs">' . trans('bases::tables.show_from') . '</span> _START_ ' . trans('bases::tables.to') . ' _END_ ' . trans('bases::tables.in') . ' <span class="badge bold badge-dt">_TOTAL_</span> <span class="hidden-xs">' . trans('bases::tables.records') . '</span></span>',
                    'infoEmpty' => trans('bases::tables.no_record'),
                    'infoFiltered' => '(' . trans('bases::tables.filtered_from') . ' _MAX_ ' . trans('bases::tables.records') . ')',
                    'lengthMenu' => '<span class="dt-length-style">_MENU_</span>',
                    'search' => '',
                    'zeroRecords' => trans('bases::tables.no_record'),
                    'processing' => '<img src="' . url('/vendor/core/images/loading-spinner-blue.gif') . '" />',
                ],
            ]);
    }

    /**
     * @return string
     * @author Sang Nguyen
     */
    public function htmlInitComplete()
    {
        return 'function () {
                $(".checkboxes").uniform();
                $(".dataTables_wrapper").css({"width": $(".dataTable").width()});
                
                var index = 0;
                var totalLength = this.api().columns().count();
                var tr = document.createElement("tr");
                $(tr).prop("role", "row").addClass("dataTable-custom-filter").css({"display": "none"});
                this.api().columns().every(function () {
                    var column = this;
                
                    index++;
                    $(document.createElement("th")).appendTo($(tr));
                    if (index == totalLength) {
                        var searchBtn = document.createElement("a");
                        $(searchBtn).addClass("btn btn-info btn-sm btn-search-table tip").attr("data-original-title", "Search").appendTo($(tr).find("th:nth-child(" + index + ")")).html("<i class=\'fa fa-search\'></i>");
                        var clearBtn = document.createElement("a");
                        $(clearBtn).addClass("btn btn-warning btn-sm btn-reset-table tip").attr("data-original-title", "Clear search").appendTo($(tr).find("th:nth-child(" + index + ")")).html("<i class=\'fa fa-times\'></i>");
                    } else if ($(column.footer()).hasClass("searchable")) {
                
                        var input = document.createElement("input");
                        $(input).addClass("form-control input-sm");
                
                        var placeholder = "Search...";
                        if ($(column.footer()).hasClass("searchable_id")) {
                            placeholder = "...";
                        }
                        $(input).prop("type", "text").css("width", "100%").prop("placeholder", placeholder).appendTo($(tr).find("th:nth-child(" + index + ")"))
                            .on("change", function () {
                                column.search($(this).val()).draw();
                            });
                    } else if ($(column.footer()).hasClass("column-select-search")) {
                        var select = $(\'<select class="form-control input-sm" data-placeholder="Select to filter"><option value="">Select to filter</option></select>\')
                            .appendTo($(tr).find("th:nth-child(" + index + ")"))
                            .on("change", function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                
                                column.search(val ? "^" + val + "$" : "", true, false).draw();
                            });
                
                        column.data().unique().sort().each(function (d, j) {
                            var value = $(d).data("value");
                            var text = $(d).data("text");
                            if (column.search() === "^" + value + "$") {
                                select.append(\'<option value="\' + value + \'" selected="selected">\' + text + \'</option>\')
                            } else {
                                select.append(\'<option value="\' + value + \'">\' + text + \'</option>\')
                            }
                        });
                    }
                });
                $(tr).appendTo($("#dataTableBuilder thead"));
                
                if (jQuery().select2) {
                    $(document).find(\'.select-multiple\').select2({
                        width: \'100%\',
                        allowClear: true,
                        placeholder: $(this).data(\'placeholder\')
                    });
                    $(document).find(\'.select-search-full\').select2({
                        width: \'100%\'
                    });
                    $(document).find(\'.select-full\').select2({
                        width: \'100%\',
                        minimumResultsForSearch: -1
                    });
                }
            }';
    }

    /**
     * @return string
     * @author Sang Nguyen
     */
    public function htmlDrawCallback()
    {
        return 'function () {
                var pagination = $(this).closest(\'.dataTables_wrapper\').find(\'.dataTables_paginate\');
                pagination.toggle(this.api().page.info().pages > 1);
                
                var data_count = this.api().data().count();
                
                var length_select = $(this).closest(\'.dataTables_wrapper\').find(\'.dataTables_length\');
                var length_info = $(this).closest(\'.dataTables_wrapper\').find(\'.dataTables_info\');
                length_select.toggle(data_count >= 10);
                length_info.toggle(data_count > 0);
                
                if (data_count > 0) {
                    $(".page-content .dataTables_wrapper").css("padding-bottom", "40px");
                }
                    
                $(".checkboxes").uniform();
                
                $(document).on("click", ".btn-search-table", function () {
                    $("#dataTableBuilder tfoot tr input").trigger("change");
                });
                
                if (jQuery().select2) {
                    $(document).find(\'.select-multiple\').select2({
                        width: \'100%\',
                        allowClear: true,
                        placeholder: $(this).data(\'placeholder\')
                    });
                    $(document).find(\'.select-search-full\').select2({
                        width: \'100%\'
                    });
                    $(document).find(\'.select-full\').select2({
                        width: \'100%\',
                        minimumResultsForSearch: -1
                    });
                }
            }';
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    public function getBuilderParameters()
    {
        $buttons = array_merge($this->getButtons(), $this->getActionsButton());
        return [
            'stateSave' => true,
            'buttons' => array_merge($buttons, $this->getDefaultButtons()),
        ];
    }

    /**
     * @return array
     * @author Sang Nguyen
     */
    public function getActionsButton()
    {
        return [
            [
                'extend' => 'collection',
                'text' => '<span>' . trans('bases::forms.actions') . ' <span class="caret"></span></span>',
                'buttons' => $this->getActions(),
            ],
        ];
    }

    /**
     * @return array
     * @author Sang Nguyen
     */
    public function getDefaultButtons()
    {
        return [
            'export',
            'print',
            [
                'link' => '#',
                'text' => view('bases::elements.tables.filter')->render(),
            ],
            'reset',
            'reload',
        ];
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     * @since 2.1
     */
    abstract function columns();

    /**
     * @return mixed
     * @author Sang Nguyen
     * @since 2.1
     */
    abstract function buttons();

    /**
     * @return mixed
     * @author Sang Nguyen
     * @since 2.1
     */
    abstract function actions();

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    public function getButtons()
    {
        $buttons = [];
        foreach ($this->buttons() as $key => $button) {
            if (array_get($button, 'extend') == 'collection') {
                $buttons[] = $button;
            } else {
                $buttons[] = [
                    'className' => 'action-item',
                    'text' => '<span data-action="' . $key . '" data-href="' . $button['link'] . '"> ' . $button['text'] . '</span>',
                ];
            }
        }
        return $buttons;
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    public function getActions()
    {
        $actions = [];
        foreach ($this->actions() as $key => $action) {
            $actions[] = [
                'className' => 'action-item',
                'text' => '<span data-action="' . $key . '" data-href="' . $action['link'] . '"> ' . $action['text'] . '</span>',
            ];
        }
        return $actions;
    }

    /**
     * Get columns.
     *
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    public function getColumns()
    {
        $headings = array_merge($this->getStatusColumnHeading(), $this->columns());

        $extra = apply_filters(BASE_FILTER_TABLE_HEADINGS, $headings, $this->filename());

        return array_merge($extra, $this->getOperationsHeading());
    }

    /**
     * @return array
     * @author Sang Nguyen
     */
    public function getStatusColumnHeading()
    {
        return [
            'checkbox' => [
                'width' => '10px',
                'class' => 'text-left no-sort',
                'title' => '<div class="checkbox checkbox-primary"><input type="checkbox" class="group-checkable" data-set=".dataTable .checkboxes" /></div>',
                'orderable' => false,
                'searchable' => false,
                'exportable' => false,
                'printable' => false,
            ],
        ];
    }

    /**
     * @return array
     * @author Sang Nguyen
     */
    public function getOperationsHeading()
    {
        return [
            'operations' => [
                'title' => trans('bases::tables.operations'),
                'width' => '134px',
                'class' => 'text-center',
                'orderable' => false,
                'searchable' => false,
                'exportable' => false,
                'printable' => false,
            ]
        ];
    }

    /**
     * @param array $data
     * @param array $mergeData
     * @param string $view
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @since 2.4
     * @author Sang Nguyen
     */
    public function renderTable($data = [], $mergeData = [], $view = 'bases::elements.table')
    {
        return parent::render($view, $data, $mergeData);
    }
}
