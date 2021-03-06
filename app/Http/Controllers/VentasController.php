<?php

namespace SICOVIMA\Http\Controllers;

use Illuminate\Http\Request;

use SICOVIMA\Http\Requests;
use SICOVIMA\Http\Requests\ventaRequest;
use SICOVIMA\Http\Controllers\Controller;
use SICOVIMA\cliente;
use SICOVIMA\documento;
use SICOVIMA\fecha;
use SICOVIMA\detalleVenta;
use SICOVIMA\venta;
use SICOVIMA\documentoVenta;
use SICOVIMA\clienteJuridico;
use SICOVIMA\producto;
use SICOVIMA\inventarioProductoTerminado;
use SICOVIMA\estadoDocumento;
use SICOVIMA\defectuosoPT;
use SICOVIMA\User;
use SICOVIMA\bitacora;
use Input;
use Session;


class VentasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function Modificar($id)
    {
        //echo $venta = venta::where('id',$id)->get()->first();
        $venta = venta::find($id);
        $clientes = cliente::all();
        $cliente = cliente::all();
        $productos = producto::where('estado_Prod',1)->get();
        $arrayC = [];
        foreach ($clientes as $cliente) {
            $arrayC[$cliente->id]=$cliente->nombre_Cli;
        }

        $inventarioProductoTerminados = inventarioProductoTerminado::all();
        return view('Proyecto.Desarrollo.Ventas.ModificarVenta',compact('arrayC','inventarioProductoTerminados','venta','productos','cliente','clientes'));
    }

    public function Ver($id)
    {
        $venta = venta::find($id);
        $productos = producto::where('estado_Prod',1)->get();
        $cliente = cliente::find($venta->id_Cliente);


        $inventarioProductoTerminados = inventarioProductoTerminado::all();
        return view('Proyecto.Desarrollo.Ventas.VerVenta',compact('inventarioProductoTerminados','venta','productos','cliente'));
    }

    public function Mostrar()
    {
        $ventas = venta::with('cliente')->orderBy('fecha_Ven')->get();
        return view("Proyecto.Desarrollo.Ventas.MostrarListadeVentas",compact('ventas'))->with('ventas', $ventas);
    }

    public function Index()
    {
        // producto::create([
        //     'tipo_Prod'=>"Camisa",
        //     'estilo_Prod'=>"Manga larga",
        //     'descripcion_Prod'=>"Con dos bolsas en el pecho",
        //     'precio_Prod'=>9.35,
        //     'color_Prod'=>"Negra",
        //     'talla_Prod'=>"M",
        //     'imagen_Prod'=>"",
        //     'estado_Prod'=>0,
        //     'estado2_Prod'=>0,
        //     'estado3_Prod'=>0,
        // ]);
        // producto::create([
        //     'tipo_Prod'=>"Falda",
        //     'estilo_Prod'=>"Corta",
        //     'descripcion_Prod'=>"Sin bolsas en la parte de atras",
        //     'precio_Prod'=>6.90,
        //     'color_Prod'=>"Rosado",
        //     'talla_Prod'=>"S",
        //     'imagen_Prod'=>"",
        //     'estado_Prod'=>1,
        //     'estado2_Prod'=>0,
        //     'estado3_Prod'=>0,
        // ]);

        // //
        // inventarioProductoTerminado::create([
        //         'tipoMovimiento_IPT'=>1,
        //         'existencias_IPT'=>0,
        //         'cantidad_IPT'=>15,
        //         'fechaMov_IPT'=>"2015-08-08",
        //         'nuevaExistencia_IPT'=>15,
        //         'id_Producto'=>1,
        //     ]);
        // inventarioProductoTerminado::create([
        //         'tipoMovimiento_IPT'=>1,
        //         'existencias_IPT'=>0,
        //         'cantidad_IPT'=>22,
        //         'fechaMov_IPT'=>"2016-08-09",
        //         'nuevaExistencia_IPT'=>22,
        //         'id_Producto'=>2,
        //     ]);


        $productos = producto::where('estado_Prod',1)->get();
        $cliente = cliente::all();
        $inventarioProductoTerminados = inventarioProductoTerminado::all();
        return view('Proyecto.Desarrollo.Ventas.RegistrarVentas',compact('cliente','productos'))->with('cliente', $cliente)->with('inventarioProductoTerminados', $inventarioProductoTerminados);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ventaRequest $request)
    {

        $cantidadV = $request->cantidadV;
        $idV = $request->idV;
        $costoProdV = $request->costoProdV;
        $gananciaV = $request->gananciaV;

        $datoFecha = explode("/",(String)$request->fecha_Ven);
        $fechaOrdenada = $datoFecha[2]."-".$datoFecha[1]."-".$datoFecha[0];
        $aux = cliente::find($request->clientes);
        if ($aux->tipoCli==1):
        $ju = clienteJuridico::where('id_Cliente',$aux->id)->get()->last();
        $iva = $request->total_Ven * 0.13;
        else:
        $iva=0;
        endif;
        $documento = documento::create([
            'tipo_Doc'=>1,//factura
            'tipoPago_Doc'=>2,
            'fechaEmision_Doc'=>$fechaOrdenada,
            'estado_Doc'=>0,
            'numero_Doc'=>$request->numeroDoc,
        ]);
        $contador=0;
        for ($i=0; $i < count($cantidadV); $i++) {
            $contador=$contador+$cantidadV[$i];# code...
        }

        $venta = venta::create([
            'can_Ven'=>$contador,
            'fecha_Ven'=>$fechaOrdenada,
            'total_Ven'=>$request->total_Ven+$iva,
            'id_Cliente'=>$request->clientes,
            'estado_Ven'=>0,
        ]);

        Session::flash('message','Venta registrada correctamente');

        bitacora::bitacoras('Registro','Registro de venta a cliente: '.$aux->nombre_Cli);

        for ($i=0; $i < count($idV); $i++) {
            $inventario = inventarioProductoTerminado::where('id_Producto',$idV[$i])->get()->last();
            inventarioProductoTerminado::create([
                'tipoMovimiento_IPT'=>2,
                'existencias_IPT'=>$inventario->nuevaExistencia_IPT,
                'cantidad_IPT'=>$cantidadV[$i],
                'fechaMov_IPT'=>$fechaOrdenada,
                'nuevaExistencia_IPT'=>$inventario->nuevaExistencia_IPT-$cantidadV[$i],
                'id_Producto'=>$idV[$i],
            ]);

            detalleVenta::create([
            'cant_DVen'=>$cantidadV[$i],
            'costoProd_DVen'=>$costoProdV[$i],
            'id_Venta'=>$venta->id,
            'id_Producto'=>$idV[$i],
            'gananciaProd_DVen'=>$gananciaV[$i],
            ]);# code...
        }

        $documentoVenta = documentoVenta::create([
            'id_Venta'=>$venta->id,
            'id_Documento'=>$documento->id,
        ]);
        $cl = cliente::find($venta->id_Cliente);
        if ($cl->tipo_Cli==0) {
            return redirect("Factura/1/{$venta->id}");//factura cliente
        }else if ($cl->tipo_Cli==1) {
            return redirect("FacturaCF/1/{$venta->id}");//factura credito fiscal
        }

    }

    public function report(Request $request)
    {
        $nuevaFecha = fecha::create([
            'fechai'=>$request->fecha_i,
            'fechaf'=>$request->fecha_f,
        ]);
        return redirect("Proyecto.Desarrollo.Ventas.ReportesVenta");//factura credito fiscal
    }

    public function ListaProductos()
    {
        $producto= producto::all();
        $inventarioProductoTerminado = inventarioProductoTerminado::all();
        return view("Proyecto.Desarrollo.Ventas.FormVentas.modalDetallesdeProducto")->with('producto', $producto)->with('inventarioProductoTerminado', $inventarioProductoTerminado);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $producto2 = producto::find($id);
        return view("Proyecto.Desarrollo.Ventas.RegistrarVentas")->with('producto2', $producto2);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $cantidadV=$request->cantidadV;
        $idV=$request->idV;
        $costoProdV=$request->costoProdV;
        $gananciaV=$request->gananciaV;
        $venta2 = venta::find($id);
        $venta2->estado_Ven = 1;
        $venta2->save();
        $detalles2 = detalleVenta::where('id_Venta',$id)->get();
        $docVenta = documentoVenta::where('id_Venta',$id)->get()->first();
        $doc = documento::find($docVenta->id_Documento);
        $doc->estado_Doc = 1;//estado anulada
        $doc->save();
        $datoFecha = explode("/",(String)$request->fecha_Ven);
        $fechaOrdenada = $datoFecha[2]."-".$datoFecha[1]."-".$datoFecha[0];


        $aux2 = cliente::find($venta2->id_Cliente);
        Session::flash('message','Venta modificada correctamente');

        bitacora::bitacoras('Modificacion','Modificacion de venta a cliente: '.$aux2->nombre_Cli);

        estadoDocumento::create([
            'motivo_EstadoDoc'=>$request->motivoEstado,
            'id_Documento'=>$docVenta->id_Documento,
        ]);

        foreach ($detalles2 as $detalle2) {
            $inventario = inventarioProductoTerminado::where('id_Producto',$detalle2->id_Producto)->get()->last();
            inventarioProductoTerminado::create([
                'tipoMovimiento_IPT'=>1,
                'existencias_IPT'=>$inventario->nuevaExistencia_IPT,
                'cantidad_IPT'=>$detalle2->cant_DVen,
                'fechaMov_IPT'=>$fechaOrdenada,
                'nuevaExistencia_IPT'=>$inventario->nuevaExistencia_IPT+$detalle2->cant_DVen,
                'id_Producto'=>$detalle2->id_Producto,
            ]);
        }

        $documento = documento::create([
            'tipo_Doc'=>1,//factura
            'tipoPago_Doc'=>2,
            'fechaEmision_Doc'=>$fechaOrdenada,
            'estado_Doc'=>0,//estado normal
            'numero_Doc'=>$request->numeroDoc,
        ]);

        $contador=0;
        for ($i=0; $i < count($cantidadV); $i++) {
            $contador=$contador+$cantidadV[$i];
        }

        $venta = venta::create([
            'can_Ven'=>$contador,
            'fecha_Ven'=>$fechaOrdenada,
            'total_Ven'=>$request->total_Ven,
            'id_Cliente'=>$request->clientes,
            'estado_Ven'=>0,
        ]);

        $aux = cliente::find($request->clientes);
        Session::flash('message','Venta modificada correctamente');

        bitacora::bitacoras('Registro','Nuevo registro de venta por modificacion, a cliente: '.$aux->nombre_Cli);

        for ($i=0; $i < count($idV); $i++) {

            $inventario = inventarioProductoTerminado::where('id_Producto',$idV[$i])->get()->last();

            inventarioProductoTerminado::create([
                'tipoMovimiento_IPT'=>2,
                'existencias_IPT'=>$inventario->nuevaExistencia_IPT,
                'cantidad_IPT'=>$cantidadV[$i],
                'fechaMov_IPT'=>$fechaOrdenada,
                'nuevaExistencia_IPT'=>$inventario->nuevaExistencia_IPT-$cantidadV[$i],
                'id_Producto'=>$idV[$i],
            ]);

            detalleVenta::create([
            'cant_DVen'=>$cantidadV[$i],
            'costoProd_DVen'=>$costoProdV[$i],
            'id_Venta'=>$venta->id,
            'id_Producto'=>$idV[$i],
            'gananciaProd_DVen'=>$gananciaV[$i],
            ]);# code...

        }

        $documentoVenta = documentoVenta::create([
            'id_Venta'=>$venta->id,
            'id_Documento'=>$documento->id,
        ]);
        $cl = cliente::find($venta->id_Cliente);
        if ($cl->tipo_Cli==0) {
            return redirect("Factura/1/{$venta}");//factura cliente
        }else if ($cl->tipo_Cli==1) {
            return redirect("FacturaCF/1/{$venta}");//factura credito fiscal
        }



        // $cantidadV=$request->cantidadV;
        // $idV=$request->idV;
        // $costoProdV=$request->costoProdV;
        // $gananciaV=$request->gananciaV;
        // $cantidadV=$request->cantidadV;
        // $iddv=$request->id;

        // $venta = venta::find($id);
        // $contador=0;
        // for ($i=0; $i < count($cantidadV); $i++) {
        //     $contador=$contador+$cantidadV[$i];# code...
        // }
        // $venta -> total_Ven = $request -> total_Ven;
        // $venta -> id_Cliente = $request -> clientes;
        // $venta -> fecha_Ven = $request -> fecha_Ven;
        // $venta -> cantidad_Ven = $contador;
        // $venta -> save();

        // $detalles = detalleVenta::where('id_Venta',$id)->get();
        // foreach ($detalles as $detalle) {
        //     $detalle->delete();
        // }
        //    for ($i=0; $i < count($idV); $i++) {
        //     if ($iddv[$i]!="") {
        //         $detalle = new detalleVenta();
        //         $detalle->id=$iddv[$i];
        //         $detalle->cant_DVen=$cantidadV[$i];
        //         $detalle->costoProd_DVen=$costoProdV[$i];
        //         $detalle->id_Venta=$venta->id;
        //         $detalle->id_Producto=$idV[$i];
        //         $detalle->gananciaProd_DVen=$gananciaV[$i];
        //         $detalle->save();
        //     }else{
        //         detalleVenta::create([
        //         'cant_DVen'=>$cantidadV[$i],
        //         'costoProd_DVen'=>$costoProdV[$i],
        //         'id_Venta'=>$venta->id,
        //         'id_Producto'=>$idV[$i],
        //         'gananciaProd_DVen'=>$gananciaV[$i],
        //         ]);# code...
        //     }
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public static function responsables($id){
        $responsable = clienteJuridico::where('id_Cliente','=',$id)->get()->first();
        if (count($responsable)>0) {
            return $responsable;# code...
        }else{
            return "false";
        }

    }

    public static function motivoss($id){
        $docu = documentoVenta::where('id_Venta','=',$id)->get()->first();
        $xx = estadoDocumento::where('id_Documento','=',$docu->id_Documento)->get()->first();
        if (count($xx)>0) {
            return $xx;# code...
        }else{
            return "false";
        }

    }

    public static function AddMotivoVenta($idMot,$motivo){

        $docVenta = documentoVenta::where('id_Venta',$idMot)->get()->first();
        $doc = documento::find($docVenta->id_Documento);
        $doc->estado_Doc = 1;//estado anulada
        $doc->save();
        $venta3 = venta::find($idMot);
        $venta3->estado_Ven = 1;//Anulada-Eliminada
        $venta3->save();

        $aux = cliente::find($venta3->id_Cliente);
        Session::flash('message','Anulacion realizada correctamente');

        bitacora::bitacoras('Anulacion','Anulacion de venta '.$venta3->id.' a cliente: '.$aux->nombre_Cli);

        estadoDocumento::create([
            'motivo_EstadoDoc'=>$motivo,
            'id_Documento'=>$docVenta->id_Documento,
        ]);
        $ventas = venta::with('cliente')->get();
        return 0;
    }

    public function Reportes()
    {
        $ventas = venta::with('cliente')->get();
        return view("Proyecto.Desarrollo.Ventas.ReportesVenta",compact('ventas'))->with('ventas', $ventas);
    }

    public function FacturaCF($tipo,$ventas)
    {
        $vistaurl="Proyecto.Desarrollo.Ventas.FacturaCF";
        return $this->crearFacturaCF($ventas,$vistaurl,$tipo);
    }

    public function crearFacturaCF($datos,$vistaurl,$tipo)
    {
        $data = $datos;
        $date = date('Y-m-d');
        $view = \View::make($vistaurl, compact('data','date'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        if ($tipo==1) {
            return $pdf->stream('FacturaCF.pdf');
        }
        if ($tipo==2) {
            return $pdf->download('FacturaCreditoFiscal.pdf');
        }

    }

    public function crearFactura($datos,$vistaurl,$tipo)
    {
        $data = $datos;
        $date = date('Y-m-d');
        $view = \View::make($vistaurl, compact('data','date'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        if ($tipo==1) {
            return $pdf->stream('Factura.pdf');
        }
        if ($tipo==2) {
            return $pdf->download('Factura.pdf');
        }

    }

    public function Factura($tipo,$ventas)
    {
        $vistaurl="Proyecto.Desarrollo.Ventas.Factura";
        return $this->crearFactura($ventas,$vistaurl,$tipo);
    }

    public function Ayuda()
    {
        echo "hola";
    }

    public function crearReporteTodasVentas($vistaurl,$tipo)
    {
        $date = date('Y-m-d');
        $view = \View::make($vistaurl, compact('date'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        if ($tipo==1) {
            return $pdf->stream('ReporteTodasVentas.pdf');
        }
        if ($tipo==2) {
            return $pdf->download('ReporteTodasVentas.pdf');
        }

    }

    public function ReporteTodasVentas($tipo)
    {
        $vistaurl="Proyecto.Desarrollo.Ventas.ReporteTodasVentas";
        return $this->crearReporteTodasVentas($vistaurl,$tipo);
    }

    public function crearReporteVentaCN($vistaurl,$tipo)
    {
        $date = date('Y-m-d');
        $view = \View::make($vistaurl, compact('date'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        if ($tipo==1) {
            return $pdf->stream('ReporteVentaClienteNatural.pdf');
        }
        if ($tipo==2) {
            return $pdf->download('ReporteVentaClienteNatural.pdf');
        }

    }

    public function ReporteVentaCN($tipo)
    {
        $vistaurl="Proyecto.Desarrollo.Ventas.ReporteVentaCN";
        return $this->crearReporteVentaCN($vistaurl,$tipo);
    }

    public function crearReporteVentaCJ($vistaurl,$tipo)
    {
        $date = date('Y-m-d');
        $view = \View::make($vistaurl, compact('date'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        if ($tipo==1) {
            return $pdf->stream('ReporteVentaClienteJuridico.pdf');
        }
        if ($tipo==2) {
            return $pdf->download('ReporteVentaClienteJuridico.pdf');
        }

    }

    public function ReporteVentaCJ($tipo)
    {
        $vistaurl="Proyecto.Desarrollo.Ventas.ReporteVentaCJ";
        return $this->crearReporteVentaCJ($vistaurl,$tipo);
    }

    public function crearReporteVentasA($vistaurl,$tipo)
    {
        $date = date('Y-m-d');
        $view = \View::make($vistaurl, compact('date'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        if ($tipo==1) {
            return $pdf->stream('ReporteVentasAnuladas.pdf');
        }
        if ($tipo==2) {
            return $pdf->download('ReporteVentasAnuladas.pdf');
        }
    }

    public function ReporteVentasA($tipo)
    {
        $vistaurl="Proyecto.Desarrollo.Ventas.ReporteVentasA";
        return $this->crearReporteVentasA($vistaurl,$tipo);
    }

}
