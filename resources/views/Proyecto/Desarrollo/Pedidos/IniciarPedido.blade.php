<?php
use SICOVIMA\producto;

 ?>

@extends('layouts.MenuAdministrador')

@section('content')

  {!! Form::open(['route'=>['Pedidos.update',$detallePedido_e->id], 'method'=>'PUT','autocomplete'=>'off']) !!}

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
        <h2>Iniciar Pedido</h2>
        <ol class="breadcrumb">
            <li>
                <a href="index.html">Pedidos</a>
            </li>
            <li class="active">
                <strong>Iniciar Pedido</strong>
            </li>
        </ol>
    </div>
    <div class="col-sm-8">

    </div>
</div>

<div id="contenido">
<br>

<div class="row">
    <div class="col-lg-7">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Pedido</h5>
            </div>
            <div class="ibox-content">
            <br>

                    <div class="row">
                    	<div class="col-md-1">
                    	</div>
                        <label class="col-lg-2 control-label">Cliente</label>
                        <div class="col-md-3">
                            <div class="input-group">
                            	{!! Form::text('nombre_Cli',$detallePedido_e->pedido->cliente->nombre_Cli,['class'=>'form-control', 'name'=>'clientes_I','id'=>'clientes_I','disabled'=>'disabled','readonly'=>'readonly']) !!}
                            	<!-- Este es no editable -->
                            </div>
                        </div>
                    </div>

                    <div class="row">
                    <br>
                    <div class="col-md-1">
                   	</div>
                    <label class="col-lg-2 control-label">Responsable</label>
                        <div class="col-lg-8">
                            <div class="input-group">
                              <?php
                              if ($detallePedido_e->pedido->cliente->tipo_Cli === 'FALSE') {
                                # code...
                                ?>
                                {!! Form::text('nombreResponsable_I','',['class'=>'form-control','name'=>'Responsable_IN','disabled'=>'disabled','readonly'=>'readonly']) !!}
                              	<!-- Este es no editable -->
                                <?php
                              }else {
                                $Responsable=\SICOVIMA\clienteJuridico::where('id_Cliente', $detallePedido_e->pedido->cliente->id)->value('nombreResponsable_CJ');
                                // $nombre = \SICOVIMA\clienteJuridico::find($Responsable->id);

                                ?>
                                {!! Form::text('nombreResponsable_I',$Responsable,['class'=>'form-control','name'=>'Responsable_IJ','disabled'=>'disabled','readonly'=>'readonly']) !!}
                              	<!-- Este es no editable -->

                                 <?php
                                   }
                               ?>

                            </div>
                        </div>
                    </div>

                    <div class="row"  id="data_2">
                    <br>
                    <div class="col-md-1">
                    </div>
                    <label class="col-lg-2 control-label">Fecha de Realizacion</label>
                        <div class="col-md-4">
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <?php   $dato = explode("-",(String)$detallePedido_e->pedido->fecha_Ped);
                                        $fecha = $dato[2]." / ".$dato[1]." / ".$dato[0];
                                ?>
                                {!! Form::text('fecha_Ped',$fecha ,['class'=>'form-control','name'=>'fecha_Realizacion','disabled'=>'disabled']) !!}
                                <!-- Este es no editable -->
                            </div>
                        </div>
                    </div>
                    <br>
            </div>
        </div>
    </div>


    <div class="col-lg-5">



        <div class="ibox-content">
            <div class="carousel slide" id="carousel1">
                <div class="carousel-inner">

                    <div class="item active">
                        <img alt="image" class="img-responsive" src={{ asset('/img/Mada-Denim-Blanco4.jpg') }}>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

@include('Proyecto.Desarrollo.Pedidos.Form.IniciarPedido.tablaDetallePedido')

<div class="row">
    <div class="ibox">
        <div class="ibox-content">
            <div class="row">
            <br>
                <div class="col-xs-4">
                </div>

                <div class="col-xs-2">
                    <div class="input-group bootstrap-touchspin" id="Prueba">

                      <input type="hidden" name="id" value='100'/>
                      <a type="button" class="btn btn-outline btn-primary dim" id="AddCant" data-toggle="modal" data-target="#myModalTabla">Agregar Materia Prima</a>

                 </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('Proyecto.Desarrollo.Pedidos.Form.IniciarPedido.tablaDetalleDelProducto')

<div class="row">
    <div class="ibox">
        <div class="ibox-content">
            <div class="row">
            <br>
                <div class="col-xs-4">
                </div>

                <div class="col-xs-2">
                    <div class="input-group bootstrap-touchspin">
                        {!! Form::submit('Iniciar',['class'=>'btn btn-primary']) !!}
                    </div>
                </div>
              
            </div>
        </div>
    </div>
</div>



@include('Proyecto.Desarrollo.Pedidos.Form.IniciarPedido.modalDetalle')

<div class="modal inmodal" id="myModal9" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <i class="fa fa-clipboard modal-icon"></i>
                <h4 class="modal-title">Materia Prima</h4>
                <small>Modifique la materia prima necesaria para su pedido</small>
            </div>


            <div class="modal-body">
                 <div class="ibox-content">
                <form class="form-horizontal">
                    <br>

                    <div class="form-group"><label class="col-lg-3 control-label">Tipo</label>

                        <div class="col-lg-7">
                        	{!! Form::select('tipo_MP', ['1' => 'Tela', '2' => 'Hilo', '3' => 'Zipper', '4' => 'Boton', '5' => 'Remache'],'Seleccionar' ,['class' => 'chosen-select','data-placeholder'=>'Seleccionar','style' => 'width:300px','tabindex' => '2']) !!}
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-3 control-label">Materia Prima</label>

                        <div class="col-lg-9">

                            <div class="input-group">
                                {!! Form::select('nombre_MP', ['1' => 'Denim'],'Seleccionar' ,['class' => 'chosen-select','data-placeholder'=>'Seleccionar','style' => 'width:300px','tabindex' => '2']) !!}
                            </div>

                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-3 control-label">Precio</label>
                        <div class="col-lg-4">
                            <div class="input-group m-b">
                                <span class="input-group-addon">$</span>
                                {!! Form::number('precio_MP',null,['class'=>'touchspin2 form-control','step'=>'any']) !!}

                            </div>
                       </div>
                    </div>
                    <div class="form-group"><label class="col-lg-3 control-label">Cantidad</label>
                        <div class="col-lg-2">
                            {!! Form::number('cant_DPed',null,['class'=>'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">Subtotal</label>
                        <div class="col-lg-4">
                            <div class="input-group m-b">
                                <span class="input-group-addon">$</span>
                                {!! Form::number('subTotal_DPed',null,['class'=>'touchspin2 form-control','step'=>'any']) !!}
                            </div>
                       </div>
                    </div>
                </form>
            </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Modificar</button>
                <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
{!!Form::close()!!}
@stop
