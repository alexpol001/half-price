<template>
    <div class="row">
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">DataTable with default features</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="dataTables_wrapper dt-bootstrap4">
                    <table id="uwt_catalog_gridview" class="table table-bordered table-striped dataTable" role="grid"
                           aria-describedby="uwt_catalog_gridview_info">
                        <thead>
                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="uwt_catalog_gridview" rowspan="1"
                                colspan="1" style="width: 282.35px;" aria-sort="ascending"
                                aria-label="Rendering engine: activate to sort column descending">Rendering engine
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="uwt_catalog_gridview" rowspan="1"
                                colspan="1" style="width: 359.283px;"
                                aria-label="Browser: activate to sort column ascending">Browser
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="uwt_catalog_gridview" rowspan="1"
                                colspan="1" style="width: 320.483px;"
                                aria-label="Platform(s): activate to sort column ascending">Platform(s)
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="uwt_catalog_gridview" rowspan="1"
                                colspan="1" style="width: 242.917px;"
                                aria-label="Engine version: activate to sort column ascending">Engine version
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="uwt_catalog_gridview" rowspan="1"
                                colspan="1" style="width: 175.967px;"
                                aria-label="CSS grade: activate to sort column ascending">CSS grade
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr role="row" class="odd">
                            <td>Gecko</td>
                            <td>Firefox 1.0</td>
                            <td>Win 98+ / OSX.2+</td>
                            <td>1.7</td>
                            <td>A</td>
                        </tr>
                        <tr role="row" class="even">
                            <td>Gecko</td>
                            <td>Firefox 1.5</td>
                            <td>Win 98+ / OSX.2+</td>
                            <td>1.8</td>
                            <td>A</td>
                        </tr>
                        <tr role="row" class="odd">
                            <td>Gecko</td>
                            <td>Firefox 1.0</td>
                            <td>Win 98+ / OSX.2+</td>
                            <td>1.7</td>
                            <td>A</td>
                        </tr>
                        <tr role="row" class="even">
                            <td>Gecko</td>
                            <td>Firefox 1.5</td>
                            <td>Win 98+ / OSX.2+</td>
                            <td>1.8</td>
                            <td>A</td>
                        </tr>
                        <tr role="row" class="odd">
                            <td>Gecko</td>
                            <td>Firefox 2.0</td>
                            <td>Win 98+ / OSX.2+</td>
                            <td>1.8</td>
                            <td>A</td>
                        </tr>
                        <tr role="row" class="even">
                            <td>Gecko</td>
                            <td>Firefox 3.0</td>
                            <td>Win 2k+ / OSX.3+</td>
                            <td>1.9</td>
                            <td>A</td>
                        </tr>
                        <tr role="row" class="odd">
                            <td>Gecko</td>
                            <td>Camino 1.0</td>
                            <td>OSX.2+</td>
                            <td>1.8</td>
                            <td>A</td>
                        </tr>
                        <tr role="row" class="even">
                            <td>Gecko2</td>
                            <td>Camino 1.5</td>
                            <td>OSX.3+</td>
                            <td>1.8</td>
                            <td>A</td>
                        </tr>
                        <tr role="row" class="odd">
                            <td class="sorting_3">Gecko3</td>
                            <td>Netscape 7.2</td>
                            <td>Win 95+ / Mac OS 8.6-9.2</td>
                            <td>1.7</td>
                            <td>A</td>
                        </tr>
                        <tr role="row" class="even">
                            <td>Gecko</td>
                            <td>Netscape Browser 8</td>
                            <td>Win 98SE+</td>
                            <td>1.7</td>
                            <td>A</td>
                        </tr>
                        <tr role="row" class="odd">
                            <td>Gecko</td>
                            <td>Netscape Navigator 9</td>
                            <td>Win 98+ / OSX.2+</td>
                            <td>1.8</td>
                            <td>A</td>
                        </tr>
                        <tr role="row" class="even">
                            <td>Gecko</td>
                            <td>Mozilla 1.0</td>
                            <td>Win 95+ / OSX.1+</td>
                            <td>1</td>
                            <td>A</td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th rowspan="1" colspan="1">Rendering engine</th>
                            <th rowspan="1" colspan="1">Browser</th>
                            <th rowspan="1" colspan="1">Platform(s)</th>
                            <th rowspan="1" colspan="1">Engine version</th>
                            <th rowspan="1" colspan="1">CSS grade</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</template>

<script>
    export default {
        name: "DataTableComponent"
    }
</script>

<style scoped>

</style>
