@extends('layouts.MenuAdministrador')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
        <h2>Modificar Usuario</h2>
        <ol class="breadcrumb">
            <li>
                <a href="index.html">Usuario</a>
            </li>
            <li class="active">
                <strong>Modificar Usuario</strong>
            </li>
        </ol>
    </div>
    <div class="col-sm-8">

    </div>
</div>

<br>
<div id="contenido">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5></h5>

                                </div>
                                <div class="ibox-content">
                                  {!! Form::open(['route'=>['Usuario.update',$Usu->id],'class'=>'form-horizontal','method'=>'PUT','autocomplete'=>'off']) !!}

                                     <br>

                                          <div class="form-group"><label class="col-lg-3 control-label">Nombre </label>

                                            <div class="col-lg-9">
                                              <input id="name" name="name" type="text" class="form-control required" aria-required="true" value="{{$Usu->name}}">
                                            </div>
                                        </div>

                                        <div class="form-group"><label class="col-lg-3 control-label">Tipo de Usuario </label>

                                            <div class="col-lg-5">
                                              <?php
                                              if ($Usu->tipo === "Administrador") {
                                                # code...

                                               ?>
                                              <div class="radio radio-info radio-inline">
                                          <input type="radio" id="inlineRadio1" value="Administrador" name="radioInline" checked="" >
                                          <label for="inlineRadio1"> Administrador</label>
                                              </div>

                                              <div class="radio radio-inline">
                                          <input type="radio" id="inlineRadio2" value="Usuario Standard" name="radioInline" >
                                          <label for="inlineRadio2"> Standard </label>
                                              </div>
                                              <?php }else { ?>
                                                <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="Administrador" name="radioInline"  >
                                            <label for="inlineRadio1"> Administrador</label>
                                                </div>

                                                <div class="radio radio-inline">
                                            <input type="radio" id="inlineRadio2" value="Usuario Standard" name="radioInline" checked="" >
                                            <label for="inlineRadio2"> Standard </label>
                                            </div>
                                            <?php  } ?>


                                            </div>
                                        </div>


                                          <div class="form-group"><label class="col-lg-3 control-label">Correo Electronico </label>
                                        <div class="col-lg-9">
                                          <input type="email" name="email" class="form-control" value="{{$Usu->email}}">

                                        </div>

                                       </div>

                                        <div class="form-group"><label class="col-lg-3 control-label">Contraseña</label>

                                            <div class="col-lg-5">
                                            <input type="text" class="form-control" name="password" id="password" placeholder="************" >

                                            </div>

                                        </div>



                                     <div class="col-sm-offset-4">
                                        {!! Form::submit('Modificar',['class'=>'btn btn-outline btn-primary']) !!}
                                    </div>

                                    {!! Form::close() !!}
                                </div><div class="form-group">
                            </div>
                        </div>
                </div>


<div class="col-lg-5">
        <div class="ibox-content">
            <div class="carousel slide" id="carousel1">
                <div class="carousel-inner">
                    <div class="item active">
                        <img alt="image" class="img-responsive" src="{{asset('img/Mada-Denim-Blanco3.jpg')}}">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


@stop
