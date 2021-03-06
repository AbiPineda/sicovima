@extends('layouts.MenuAdministrador')

@section('content')

  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-6">
      <h2>Lista de Proveedores</h2>
      <ol class="breadcrumb">
        <li>
          <br>
          <a href={!! asset('MostrarListaProv') !!}>Proveedores</a>
        </li>
          <li class="active">
          <strong>Lista de Proveedores</strong>
        </li>
      </ol>
    </div>
    <div class="col-sm-8">
    </div>
  </div>
  <br>

 <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="row">
          <div class="col-lg-12">
            <div class="ibox float-e-margins">
              <div class="ibox-title">
                <h5>Listado de Proveedores</h5>
              </div>
              <div class="ibox-content">
                <div class="table-responsive">
                  <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <table id="example" class="table table-striped table-bordered display" cellspacing="100" width="100%">
                        <thead>
                          <tr>
                                <th align="left">Nombre</th>
                                <th align="left">Telefono</th>
                                <th align="left">Opciones</th>

                         </tr>
                        </thead>
                        <tbody>
                          @foreach($proveedor as $prov)
                            <tr>
                                <td align="left"><font size="4" >{{$prov-> nombre_Prov}}</font></td>
                                <?php
                                  $telefonoP = SICOVIMA\proveedor::numeroTelefono($prov->id);
                                ?>
                                <td align="rihgt"><font size="4" >{{$telefonoP}}</font></td>
                                <td align="center">
                                  <a href="VerProveedor/{{$prov->id}}" class="btn btn-primary btn-circle" type="button"><i class="fa fa-eye"></i>
                                  </a>
                                  <a href="ModificarProv/{{$prov->id}}" class="btn btn-success btn-circle" type="button"><i class="fa fa-pencil-square-o"></i>
                                  </a>
                                  <a href="/github/sicovima/public/darBajaProv/{{$prov->id}}" class="btn btn-warning btn-circle" type="button"><i class="fa fa-times"></i>
                                  </a>
                                </td>
                             </tr>
                          @endforeach
                        </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@stop
