 

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reporte Inventario</title>
  <style>
  footer {
      position: fixed;
      left: 0px;
      bottom: -50px;
      right: 0px;
      height: 40px;
      border-bottom: 2px solid #ddd;
    }
    footer .page:after {
      content: counter(page);
    }
    footer table {
      width: 100%;
    }
    footer p {
      text-align: right;
    }
    footer .izq {
      text-align: left;
    }
body {
    font-family: 'Source Sans Pro', sans-serif;
    font-weight: 300;
    font-size: 12px;
    margin: 0;
    padding: 0;
    color: #777777;
  }
table {
    border-collapse: collapse;
    width: 95%;
}


table th,
table td {
  text-align: center;
}

table th {
  padding: 5px 20px;
  color: white;
  border-bottom: 1px solid #C1CED9;
  white-space: nowrap;
  font-weight: normal;
}

th {
    background-color: #1890a2;
    color: white;
}

th, td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #FAFBFB;
}
  section .table-wrapper {
    position: relative;
    overflow: hidden;
  }

/*tr:nth-child(even){background-color: #F0FDFF}
*/
table tr:nth-child(2n-1) td {
  background: #F5F5F;
}
</style>
</head>
<body>
  <div class="col-md-12">
    <div class="box-body">
      <div class="box-header with-border">
        <HR style="position: absolute;left: 270px; top: 30px; z-index: 1; color:black;" width=35%>
        <div style="position: absolute;left: 280px; top: 20px; z-index: 1; color:#1890a2; font-family: fantasy; font-weight: bold;"><h2>Industrias MADA S.A de C.V</h2></div>
        <HR style="position: absolute;left: 270px; top: 50px; z-index: 1; color:black;" width=35%>
        <div style="position: absolute;left: 260px; top: 80px; z-index: 1; font-style: italic; font-weight: bold;">Fabricacion de prendas de vestir para ambos sexos</div>
        <div style="position: absolute;left: 360px; top: 110px; z-index: 1;"><h4>Teléfono 2306-5432</h4></div>
        <div style="position: absolute;left: 220px; top: 90px; z-index: 1;"><h4>Calle Las Truchas, Urb. Via del Mar #1 Nuevo Cuscatlan, La Libertad</h4></div>
        



        <HR style="position: absolute;left: 23px; top: 135px; z-index: 1; color:black;" width=93%>
        <?php   
          $dato = explode("-",(String)$date);
          $fecha = $dato[2]."/".$dato[1]."/".$dato[0];
          ?>
        <div style="position: absolute;left: 550px; top: 145px; z-index: 1;">Fecha:  <?=  $fecha; ?> </div>
        <h3 align="right" style="position: absolute;left:40; top:10px; px; z-index: 1;"><img class="al img-responsive" alt="image" width="110px" height="110px" src="img/SICOVIMAAqua1.png" ></h3>
<!-- <img alt="image" class="img-responsive" src="../img/Mada-Denim-Blanco4.jpg"> -->
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php  

        $producto = SICOVIMA\producto::all();
        $inventario = SICOVIMA\inventarioProductoTerminado::where('id_Producto',22)->get()->last();
        
           ?>
        <div style="position: absolute;left: 200px; top: 180px; z-index: 1;"><h3>PRODUCTOS TERMINADOS EN BUEN ESTADO</h3></div>
        <table class="table-wrapper" >
           <thead>
            <tr>
              <th style="color: white; font-weight: bold;">N</th> 
              <th style="color: white; font-weight: bold;">Tipo</th>
              <th style="color: white; font-weight: bold;">Estilo</th>
              <th style="color: white; font-weight: bold;">Precio</th>
              <th style="color: white; font-weight: bold;">Talla</th>
              <th style="color: white; font-weight: bold;">Existencias</th>
            </tr>
          </thead>
          <tbody>
            <?php $n = 1; ?>
            @foreach($producto as $prod)
            <?php
              $inv = SICOVIMA\inventarioProductoTerminado::where('id_Producto',$prod->id)->get()->last(); ?>

             <?php if (count($inv)!=0 && $prod->estado2_Prod==0): ?>
            <tr>
              <td style = "width:10%">{{$n}}</td> 
              <td style = "width:20%">{{$prod->tipo_Prod}}</td>
              <td style = "width:30%">{{$prod->estilo_Prod}}</td>
              <td style = "width:20%"><i class="fa fa-usd"></i>  {{$prod->precio_Prod}}</td>
              <td style = "width:20%">{{$prod->talla_Prod}}</td>
              <td style = "width:20%">{{$inv->nuevaExistencia_IPT}}</td>
            </tr>
            <?php else: ?>
            <?php endif ?>
            <?php $n++; ?>
          @endforeach
        </tbody>
      </table>
      <footer>
    <table>
      <tr>
        <td>
            <p class="izq">
              Industrias MADA S.A de C.V
            </p>
        </td>
        <td>
          <p class="page">
            Página
          </p>
        </td>
      </tr>
    </table>
  </footer>
     </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div>
</body>
</html>
