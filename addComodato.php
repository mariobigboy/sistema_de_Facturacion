<?php require_once('header.php'); 
isset($_GET['id']) ? $id=$_GET['id'] : $id=-1;
//print_r($_SESSION['user']);
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Nuevo Préstamo</h1>
	</div>
	<div class="content-header-right">
		<a  class="btn btn-primary btn-sm" href="comodato.php"> <i class="fa fa-list-ul"></i> Ver Todos</a>
		<!-- <a href="clientes-add.php" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Nuevo Cliente</a> -->
		<!--<a href="libs/codebar/index.php" class="btn btn-warning btn-sm" target="_blank"> <i class="fa fa-barcode"></i> Imprimir codebars</a>-->

	</div>
	
</section>

<?php
	//$statement = $pdo->prepare("SELECT * FROM tbl_top_category");
	//$statement->execute();
	//$total_top_category = $statement->rowCount();
?>

<section class="content">

	<?php if ($id==-1) {?>
		


		<div class="box box-info" id="buscador">
			<div class="box-body">
				
				
				<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label for="" class="col-sm-3 control-label"><i class="fa fa-search"></i> Seleccione Cliente <span>*</span></label>
						<div class="col-sm-6">
							<input type="text" name="qtxt" id="txtBuscador" class="form-control">
							<div id="results"></div> <a href="clientes-add.php" class="btn btn-info" id="btnNuevoCliente" style="margin-top: 10px;"> <i class="fa fa-user-plus"></i> Nuevo Cliente</a>
						</div>
					</div>
				</form>
			</div>
		</div>

		<div class="box box-info" id="resultados" style="display: none;">
			<div class="box-body">
				<table class="table table-bordered table-striped" id="tablaCliente" style="cursor: pointer;">
					<thead>
						<th>#</th>
						<th>Apellido</th>
						<th>Nombre</th>
						<th>Cuit</th>
						<th>Cond IVA</th>
					</thead>
					<tbody>
						
					</tbody>
					
				</table>
			</div>
		</div>

		<div class="box box-info" id="membrete" style="display:none;">
			<div class="box-body">
				<div id="cuadro" style="background-color: #ffdc4c; border-radius: 5px; padding: 2em; box-shadow: 1px 1px 5px #000;">
					<input type="hidden" id="idCliente" name="idCliente" value="" class="form-control">
					<h2>Datos del Cliente</h2>
					<div id="cuadro1" style="background-color: #000000; border-radius: 5px; padding: 2em; box-shadow: 1px 1px 5px #000;">
						<div class="row" >
							<div class="col-md-4">
								
								<h5 style="color: #ffdc4c" id="apellido">Apellido:</h5>
								<h5 style="color: #ffdc4c" id="nombre">Nombre:</h5>
								<h5 style="color: #ffdc4c" id="cuit">Cuit:</h5>
								<h5 style="color: #ffdc4c" id="iva">Condición del IVA:</h5>
							</div>
							<div class="col-md-4">
								<h5 style="color: #ffdc4c" id="tel">Tel:</h5>
								<h5 style="color: #ffdc4c" id="email">Email:</h5>
								<h5 style="color: #ffdc4c" id="localidad">Localidad: </h5>
							</div>
						</div>
					</div>
				</div>

				<hr>

				<h2>Productos</h2>

				<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
					
					<div class="form-group">
						<label for="" class="col-sm-3 control-label"><i class="fa fa-building"></i> Seleccione Sucursal <span>*</span></label>
						<div class="col-sm-6">
							<select id="idSucursal" name="idSucursal" class="form-control" required>
								<?php if (($_SESSION['user']['role']== "Admin") || ($_SESSION['user']['role']== "Super Admin")) { 

									$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_active = 1");
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);
									echo "<option value=''>Seleccione</option>";
									foreach($result as $row){

										?>

										<option value="<?php echo $row['s_id']; ?>"><?php echo $row['s_name']; ?></option>
										<?php
									}

								}else{
									$ide= $_SESSION['user']['sucursal'];
									$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_id = '$ide'");
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);
									foreach($result as $row){

										?>
										<option value="<?php echo $row['s_id']; ?>"><?php echo $row['s_name']; ?></option>
									<?php }	 
								} ?>
							</select>
						</div>
					</div>

					
					<div class="form-group">
						<label for="" class="col-sm-3 control-label"><i class="fa fa-search"></i> Seleccione Productos <br> <span id="msjModo"> Modo Manual </span></label>
						<div class="col-sm-6">
							<input type="text" name="qtxt" id="txtBuscadorProductos" class="form-control">
							<input type="text" id="entrada" disabled class="form-control">
							<div id="resultsProductos"></div>
							<div class="btn-group" role="group" >
								<a  class="btn btn-default" id="btnPedido">Producto por pedido</a>
								<!--<a  class="btn btn-info" id="btnPromo">Promos</a>-->
								<a  class="btn btn-danger" id="btnEliminarLista">Limpiar Detalles</a>
								<a  class="btn btn-warning" id="btnScan">Escanear</a>
							</div>
						</div>
					</div>
				</form>
				<div id="resultsProd"></div>
				<ul id="resultadosProductos" style="list-style: none; cursor: pointer;"></ul>
				<hr>

				<h3>Productos a Prestar:</h3>

				<table class="table " id="tablaProductosVenta" style="background-color: #f0f0f0; box-shadow: 1px 1px 5px #000">
					<thead>
						<th>Código</th>
						<th>Descripción</th>
						<th>Cantidad</th>
						<!-- <th>Precio Unit</th>
						<th>Desc.</th>
						<th>Subtotal</th> -->
					</thead>
					<tbody>

					</tbody>
					<!--<tfoot>
						<th>Total:  $<input type="number" name="idTotalFinal" id="idTotalFinal" value="0" disabled style="margin-left: 2em;"></th>
					</tfoot>-->

				</table>
				<br><br>
				<!-- <a class="btn btn-warning" id="btnVenta" disabled> Venta</a>
				<a class="btn btn-default" id="btnPresupuesto" disabled> Presupuesto</a> -->
				
				<form action="#" class="form-horizontal">
					<div class="form-group">
						<label for="" class="col-sm-3 control-label"> <i class="fa fa-edit"></i> Observaciones</label>
						<div class="col-sm-3">
							<textarea name="" id="txtObservaciones" cols="30" rows="5"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="fechaLimite_sel" class="col-sm-3 control-label"><i class="fa fa-calendar"></i> Seleccione Fecha Límite</label>
						<div class="col-sm-3">
							<input type="text" id="fechaLimite_sel" name="fechaLimite_sel" class="form-control" required>
							<input type="hidden" id="fechaLimite" value="0">
						</div>
						<a class="btn btn-warning" id="btnGuardar" > <i class="fa fa-save"></i> Guardar Cambios</a>
					</div>
				</form>
				
				
				
			</div>
		</div>
	</div> <!-- boxbody -->
</div> <!-- membrete -->



<?php }else{ header("location: nuevaVentaCliente.php?id=".$id); ?>
			<!-- <button class="btn btn-success">Nueva Venta</button>
				<button class="btn btn-warning">Presupuesto</button> -->

			<?php } ?>
		</section>

		<div class="modal fade" id="cantidadModal" tabindex="-1" role="dialog" aria-labelledby="myLabelModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myLabelModal" style="color: #000;"><i class="fa fa-number"></i> Cantidades</h4>
					</div>
					<div class="modal-body">
						<p>¿Cantidad de producto?</p>
						<div class="col-12 text-center">
							<input type="hidden" id="prodId" value="">
							<button class="btn btn-default btnLess">-</button><input type="number" class="canTotal" style="width:2.5em; margin-right: 1em; margin-left: 1em;" value="1" min="1" ><!--<label for="" class="lblTotal" style="margin-right: 1em; margin-left: 1em;">1</label>--><button class="btn btn-default btnAdd">+</button>
						</div>

						<!--<div class="col-12 text-center">
							<strong>Descuento:    </strong><input type="number" name="descuento" id="descuento" min="0" max="100" placeholder="0" step="5" style="width: 6em;"><strong>       %</strong>
						</div>-->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"> Cancelar</button>
						<a id="btnAgregarDetalle" class="btn btn-warning"> Agregar</a>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="modificaDetalleModal" tabindex="-1" role="dialog" aria-labelledby="myLabelModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myLabelModal" style="color: #000;"><i class="fa fa-number"></i> Modificar Detalle</h4>
					</div>
					<div class="modal-body">

						<h5> <div id="idDescProductModificado"></div></h5>
						<div class="col-12 text-center">
							<input type="hidden" id="idTr" value="">
							<input type="hidden" id="prodIdModificado" value="">
							<input type="hidden" id="codIdModificado" value="">
							<input type="hidden" id="descIdModificado" value="">
							<input type="hidden" id="precIdModificado" value="">
							<input type="hidden" id="descuentoIdModificado" value="">
							<input type="hidden" id="ca" value="">
							<input type="hidden" id="cantidadInicial" value="">
							<button class="btn btn-default btnLess1">-</button><input type="number" id="cantProductModificado" style="width:2.5em; margin-right: 1em; margin-left: 1em;" value="" min="1" disabled><!--<label for="" class="lblTotal" style="margin-right: 1em; margin-left: 1em;">1</label>--><button class="btn btn-default btnAdd1">+</button>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"> Cancelar</button>
						<a id="btnEliminarDetalle" class="btn btn-danger">Eliminar</a>
						<a id="btnModificarDetalle" class="btn btn-warning"> Actualizar</a>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="ventaModal" tabindex="-1" role="dialog" aria-labelledby="myLabelModal" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myLabelModalVenta" style="color: #000;"><i class="fa fa-number"></i>Registrar Venta</h4>
					</div>
					<div class="modal-body">

						<div class="box box-info">
							<div class="box-body">
								<form id="formVenta">
									<input type="hidden" name="tipoComprobante" id="tipoComprobante">
									<input type="hidden" id="idUsuario" name="idUsuario" value="<?php echo $_SESSION['user']['full_name'];?>" class="form-control">
									<!-- <input type="hidden" id="idCliente" name="idCliente" value="" class="form-control"> -->
									<input type="hidden" id="lista" name="lista" value="" class="form-control">
									<input type="hidden" id="totalVenta" name="totalVenta" value="" class="form-control">
									<input type="hidden" name="idSucursalVenta" id="idSucursalVenta">

									<div class="form-group col-lg-12">
										
										<div class="col-lg-6">
											<label for="metodo" class="label-control col-md-6 text-right"> Método de Pago </label>
											<div class="col-lg-6">

												<select name="metodo" id="metodo" class="form-control" required>
													<option value="">Seleccione una Opción</option>
													<option value="1">Efectivo</option>
													<option value="2">Tarjeta de Crédito/Débito/Débito</option>
													<option value="3">Cheque de Terceros</option>
													<option value="4">Cuenta Corriente</option>

												</select>

											</div>
										</div>

										<div class="col-lg-6">
											<label for="metodo" class="label-control col-md-4 text-right"> A Cobrar </label>
											<div class="col-lg-4">
												<input type="number" name="aCobrar" id="aCobrar" min="0" value="0" class="form-control" required disabled>
											</div>
											<div class="col-md-2">
												<a class="btn btn-success" id="btnAgregar1"><i class="fa fa-plus"></i></a>
											</div>
											
										</div>
										<div class="col-lg-12" id="intMod1">
											<div class="form-group col-lg-6" >
												<label for="interesVenta1" class="label-control col-md-6 text-center"> Interés</label>
												<div class="col-md-6">
													<input type="number" class="form-control" name="interesVenta1" value="0" min="0" step="5" id="interesVenta1">
												</div>
											</div>
											<div class="form-group col-lg-6" >
												<label for="interesTotal1" class="label-control col-md-6 text-center"> Neto a Cobrar</label>
												<div class="col-md-6">
													<input type="number" class="form-control" name="interesTotal1" value="0" min="0" step="5" id="interesTotal1" disabled>
													<input type="hidden" name="subt1" id="subt1" value="0">
												</div>
											</div>

										</div>



									</div> <!-- form-group Padre -->



									
									<!--++++++++++++++++++++++++++++++++++++++++METODOS DE PAGO+++++++++++++++}+++++++++++++++++++++ -->

									<div id="pago1">
										<div class="form-group col-lg-12">
											
											<div class="col-lg-6">

												<div class="form-group">
													<label for="metodo1" class="label-control col-md-6 text-right"> Método de Pago 2</label>
													<div class="col-lg-6">

														<select name="metodo1" id="metodo1" class="form-control">
															<option value="">Seleccione una Opción</option>
															<option value="1">Efectivo</option>
															<option value="2">Tarjeta de Crédito/Débito/Débito</option>
															<option value="3">Cheque de Terceros</option>
															<option value="4">Cuenta Corriente</option>

														</select>
														
													</div>
												</div>
											</div>


											<div class="col-lg-6">
												<div class="form-group">
													<label for="aCobrar1" class="label-control col-md-4 text-right"> A Cobrar </label>
													<div class="col-md-4">
														<input type="number" name="aCobrar1" id="aCobrar1" min="0" value="0" class="form-control" disabled>
													</div>
													<div class="col-md-4">
														<div class="btn-group" role="group" >
															<a class="btn btn-danger" id="btnQuitar2"><i class="fa fa-minus"></i></a>
															<a class="btn btn-success" id="btnAgregar2"><i class="fa fa-plus"></i></a>

														</div>
													</div>
												</div>

											</div>
											<div class="col-lg-12" id="intMod2">
												<div class="form-group col-lg-6" >
													<label for="interesVenta2" class="label-control col-md-6 text-center"> Interés</label>
													<div class="col-md-6">
														<input class="form-control" type="number" name="interesVenta2" value="0" min="0" step="5" id="interesVenta2">
													</div>
												</div>
												<div class="form-group col-lg-6" >
													<label for="interesTotal2" class="label-control col-md-6 text-center"> Neto a Cobrar</label>
													<div class="col-md-6">
														<input type="number" class="form-control" name="interesTotal2" value="0" min="0" step="5" id="interesTotal2" disabled>
														<input type="hidden" name="subt2" id="subt2" value="0">
													</div>
												</div>
											</div>

										</div>
									</div>
									<br>


									<div id="pago2">
										<div class="form-group col-lg-12">
											
											<div class="col-lg-6">

												<div class="form-group">
													<label for="metodo2" class="label-control col-md-6 text-right"> Método de Pago 3</label>
													<div class="col-lg-6">

														<select name="metodo2" id="metodo2" class="form-control">
															<option value="">Seleccione una Opción</option>
															<option value="1">Efectivo</option>
															<option value="2">Tarjeta de Crédito/Débito</option>
															<option value="3">Cheque de Terceros</option>
															<option value="4">Cuenta Corriente</option>

														</select>

													</div>
												</div>
											</div>


											<div class="col-lg-6">
												<div class="form-group">
													<label for="aCobrar2" class="label-control col-md-4 text-right"> A Cobrar </label>
													<div class="col-md-4">
														<input type="number" name="aCobrar2" id="aCobrar2" min="0" value="0" class="form-control" disabled>
													</div>
													<div class="col-md-2">
														<a class="btn btn-danger" id="btnQuitar3"><i class="fa fa-minus"></i></a>
													</div>

												</div>
											</div>
											<div class="col-lg-12" id="intMod3">
												<div class="form-group col-lg-6" >
													<label for="interesVenta3" class="label-control col-md-6 text-center"> Interés</label>
													<div class="col-md-6">
														<input class="form-control" type="number" name="interesVenta3" value="0" min="0" step="5" id="interesVenta3">
													</div>
												</div>
												<div class="form-group col-lg-6" >
													<label for="interesTotal3" class="label-control col-md-6 text-center"> Neto a Cobrar</label>
													<div class="col-md-6">
														<input type="number" class="form-control" name="interesTotal3" value="0" min="0" step="5" id="interesTotal3" disabled>
														<input type="hidden" name="subt3" id="subt3" value="0">
													</div>
												</div>
											</div>

										</div>
									</div>





									<!--++++++++++++++++++++++++++++++++++++++++METODOS DE PAGO+++++++++++++++}+++++++++++++++++++++ -->

									<hr>

									<div class="form-group">
										<div class="col-lg-6">
											<div class="box box-info">
												<div class="box-body">

													<h4 id="pago1Text"><h5 id="met1" style="background-color: #fbca08;"></h5></h4>
													<h4 id="pago2Text"><h5 id="met2" style="background-color: #fbca08;"></h5></h4>
													<h4 id="pago3Text"><h5 id="met3" style="background-color: #fbca08;"></h5></h4>
												</div>
												
											</div>
											<div class="form-group">
												<label for="obs">Observaciones</label>
												<textarea name="obs" id="obs" class="form-control"></textarea>
											</div>

											<div class="form-group">
												<label for="">Descuento General (%)</label>
												<input type="number" id="descuento_gral" name="descuento_gral" class="form-control" style="background-color: #434343; color: #ffffff" step="5" value="0" min="0">
											</div>

										</div>

										<div class="col-lg-6">
											<div class="box box-info">
												<div class="box-body" style="background-color: #f0f0f0; padding: 1em 1em 1em 2em;" id="tagi">
													<div style="border: 4px solid #DBFF33; margin: 1em; padding: 1em;">
														<h4>Subtotal : $ <span id="subTotal"></span></h4>
													</div>
													<h4>IVA Incl. 21.0%</h4>
													<h4>Total Descuentos: $<span id="totalDescuentos"></span></h4>
													<h4>Intereses : $ <span id="totalInteres"></span></h4>
													<div id="disp" style="border: 2px solid #DBFF33; margin: 2em; padding: 1em; background-color: #DBFF33; box-shadow: 1px 1px 5px #000; border-radius: 3px">
														<h3>TOTAL: $ <span id="totalVentaFin"></span></h3>
														
													</div>
												</div>
											</div>


										</div>
									</div>

								</form>

							</div>
						</div>
						<button class="btn btn-danger" id="btnBorrar">Borrar y Empezar de Nuevo el Cálculo</button>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"> Cancelar</button>
						<a id="btnGuardarVenta" class="btn btn-warning"> Guardar</a>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="productoManualModal" tabindex="-1" role="dialog" aria-labelledby="myLabelModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myLabelModal" style="color: #000;"><i class="fa fa-number"></i> Producto por Pedido</h4>
					</div>
					<div class="modal-body">

						<form id="formProductoPedido"> 
							<input type="hidden" name="codProductoPedido" value="HD0001">
							<label for="descrProductPedido"> Descripción </label>

							<input type="text" name="descrProductPedido" id="descrProductPedido" class="form-control" required>


							<div width="80%">
								<label for="cantProductPedido"> Cantidad </label>

								<input type="number" name="cantProductPedido" id="cantProductPedido" class="form-control" min="1" value="1" placeholder="0 unds" required>



								<label for="precioProductPedido" hidden> Precio </label>

								<input type="hidden" name="precioProductPedido" id="precioProductPedido" class="form-control" min="0" placeholder="$ 0.00" >
							</div>
						</form>
						

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"> Cancelar </button>
						<a id="btnAgregarDetalleProducto" class="btn btn-warning"> Agregar</a>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="promoModal" tabindex="-1" role="dialog" aria-labelledby="myLabelModal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myLabelModal" style="color: #000;"><i class="fa fa-number"></i> Descripción de la Promo</h4>
			</div>
			<div class="modal-body">

				<form id="formPromo"> 
					<input type="hidden" name="codPromo" value="HD0002">
					<label for="descrProductPedido"> Descripción </label>

					<input type="text" name="descrPromo" id="descrPromo" class="form-control" required>


					<div width="80%">
						<label for="cantPromo"> Cantidad </label>

						<input type="number" name="cantPromo" id="cantPromo" class="form-control" min="1" value="1" placeholder="0 unds" required>



						<label for="precioPromo"> Total a descontar</label>

						<input type="number" name="precioPromo" id="precioPromo" class="form-control" min="0" placeholder="$ 0.00" min="0" required>
					</div>
				</form>


			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"> Cancelar </button>
				<a id="btnAgregarDetallePromo" class="btn btn-warning"> Agregar</a>
			</div>
		</div>
	</div>
</div>


		<?php require_once('footer.php'); ?>
		<script type="text/javascript">
			$('#pago1').hide();
			$('#pago2').hide();
			$('#btnGuardarVenta').attr('disabled',true);
			$('#intMod1').hide();
			$('#intMod2').hide();
			$('#intMod3').hide();

			$('#resultados').hide();
			$('#btnVenta').attr('disabled',true);
			$('#btnPresupuesto').attr('disabled',true);
			$('#membrete').hide();
			var codigos = [];

			$('#btnBorrar').on('click', function(){
				$('#pago1').hide();
				$('#pago2').hide();
				$('#metodo').val('0');
				$('#metodo1').val('0');
				$('#metodo2').val('0');
				$('#aCobrar1').val('0');
				$('#aCobrar2').val('0');
				$('#totalVenta').val(parseFloat($('#totalVenta').val()));
				$('#descuento_gral').val('0');
				$('#pago1Text').text('');
				$('#pago2Text').text('');
				$('#pago3Text').text('');
				$('#met1').hide();
				$('#met2').hide();
				$('#met3').hide();
				calcularTodo();
			})

			$('#idSucursal').on('change', function(){
				if ($('#idSucursal').val() != "") {
					$('#idSucursalVenta').val($('#idSucursal').val());
				}
			});

			function addslashes(string) {
				return string.replace(/\\/g, '\\\\').
				replace(/\u0008/g, '\\b').
				replace(/\t/g, '\\t').
				replace(/\n/g, '\\n').
				replace(/\f/g, '\\f').
				replace(/\r/g, '\\r').
				replace(/'/g, '\\\'').
				replace(/"/g, '\\"');
			}
			
			

			function realizacambios(){
				var idTr = $('#idTr').val();
				var idProd = $('#prodIdModificado').val();
				var idCod = $('#codIdModificado').val();
				var desc = $('#descIdModificado').val();
				var cant = $('#cantProductModificado').val();
				var prec = $('#precIdModificado').val();
				var descuento = $('#descuentoIdModificado').val();
				//elimmino el tr por el idTr
				var descu = descuento;
				descu /=100;
				$('#id'+idTr).remove();
				console.log('#id'+idTr);
				//var sub = parseFloat(cant * prec);
				var tot = (prec - (descu * prec))*cant;

				
				if(verificarSiExiste(idCod)>-1){
					var idArray = verificarSiExiste(idCod);
					codigos[idArray].cant = parseInt(cant);
					codigos[idArray].total = tot;
				}


				//crear linea
				if ($('#tablaProductosVenta tbody tr').length == 0) {
					//var linea = '<tr id ="id0" data-id ="0" data-producto="'+idProd+'" data-code="'+idCod+'" data-desc="'+desc+'" data-cant="'+cant+'" data-descuento="'+descuento+'" data-prec="'+prec+'"><th>'+idCod+'</th><td>'+desc+'</td><td>'+cant+'</td><td>$'+prec+'</td><td>'+descuento+'</td><td>$'+tot.toFixed(2)+'</td></tr>';
					var linea = '<tr id ="id0" data-id ="0" data-producto="'+idProd+'" data-code="'+idCod+'" data-desc="'+desc+'" data-cant="'+cant+'" data-descuento="'+descuento+'" data-prec="'+prec+'"><th>'+idCod+'</th><td>'+desc+'</td><td>'+cant+'</td></tr>';
				}else{
					var idT = $('table#tablaProductosVenta tbody tr:last').data('id'); 
					//var linea = '<tr id ="id'+(idT+1)+'" data-id ="'+(idT+1)+'" data-producto="'+idProd+'" data-code="'+idCod+'" data-desc="'+desc+'" data-cant="'+cant+'" data-descuento="'+descuento+'"  data-prec="'+prec+'"><th>'+idCod+'</th><td>'+desc+'</td><td>'+cant+'</td><td>$'+prec+'</td><td>'+descuento+'</td><td>$'+tot.toFixed(2)+'</td></tr>';
					var linea = '<tr id ="id'+(idT+1)+'" data-id ="'+(idT+1)+'" data-producto="'+idProd+'" data-code="'+idCod+'" data-desc="'+desc+'" data-cant="'+cant+'" data-descuento="'+descuento+'"  data-prec="'+prec+'"><th>'+idCod+'</th><td>'+desc+'</td><td>'+cant+'</td></tr>';
				}

				//insertarla en la tablaProductosVenta mismo tr?
				//$('#tablaProductosVenta > tbody').append(linea);
				//var c= parseFloat($('#ca').val());
				//$('#idTotalFinal').val(c.toFixed(2));
				$('#modificaDetalleModal').modal('hide');
				cargarCodigosEnTabla();
				
			};




	// function eliminardetalle(){

	// };

	//abro el modal
	$('#tablaProductosVenta tbody').on('click', 'tr', function(){
		var id = $(this).data('id');
		var idProd = $(this).data('producto');
		var idCod = $(this).data('code');
		var desc = $(this).data('desc');
		var cant = $(this).data('cant');
		var descuento = $(this).data('descuento');
		var prec = $(this).data('prec');
		$('#idDescProductModificado').text(desc);
		$('#idTr').val(id);
		$('#prodIdModificado').val(idProd);
		$('#codIdModificado').val(idCod);
		$('#descIdModificado').val(desc);
		$('#precIdModificado').val(prec);
		$('#descuentoIdModificado').val(descuento);
		$('#cantProductModificado').val(cant);
		$('#ca').val($('#idTotalFinal').val());
		$('#cantidadInicial').val(cant);
		$('#modificaDetalleModal').modal({backdrop: "static"});
		
	});

	//eliminar detalle

	$('#btnEliminarDetalle').on('click', function(){

		var id = $('#idTr').val();
		var prec= parseFloat($('#precIdModificado').val());
		var cant= parseInt($('#cantidadInicial').val());
		var cal = parseFloat($('#idTotalFinal').val());
		cal-= prec * cant;
		
		var existe = -1;
		if(codigos.length>0){
			for(var i = 0; i < codigos.length; i++){
				if(codigos[i].id_Dom==('id'+id)){
					existe = i;
				}
			}
		}
		if(existe>-1){
			codigos.splice(existe, 1);
		}
		
		//$('#id'+id).remove();
		$('#idTotalFinal').val(cal.toFixed(2));
		$("#modificaDetalleModal").modal('hide');
		if ($('#tablaProductosVenta tbody tr').length == 0) {
			$('#btnVenta').attr('disabled',true);
			$('#btnPresupuesto').attr('disabled',true);
		}
		cargarCodigosEnTabla();
	});
	
	$('#btnModificarDetalle').on('click', function(e){
		e.preventDefault();
		realizacambios();
	});

	
	//con el boton eliminar, eliminar el tr


	//elimina o modifica detalle

	//INTEGRAR EL SCAN DE TEST.PHP

//====================SCAN===============================

function cargarCodigosEnTabla(){
	$('#tablaProductosVenta > tbody').empty();
	var total = 0;
	codigos.forEach(function(e, i){
		//crear linea

		if ($('#tablaProductosVenta tbody tr').length == 0) {
			//$('#tablaProductosVenta > tbody').append('<tr id="'+(e.id_Dom)+'" data-id="0" data-producto="'+e.idProducto+'" data-code="'+e.codigo+'" data-desc="'+e.nombre+'" data-cant="'+e.cant+'" data-descuento="'+((e.descuento)? e.descuento : 0 )+'" data-prec="'+e.precio+'"><th>'+e.codigo+'</th> <td>'+e.nombre+'</td> <td>'+e.cant+'</td> <td>$ '+e.precio+'</td> <td>'+((e.descuento)? e.descuento : 0 )+'</td> <td>$ '+(e.total).toFixed(2)+'</td> </tr>');
			$('#tablaProductosVenta > tbody').append('<tr id="'+(e.id_Dom)+'" data-id="0" data-producto="'+e.idProducto+'" data-code="'+e.codigo+'" data-desc="'+e.nombre+'" data-cant="'+e.cant+'" data-descuento="'+((e.descuento)? e.descuento : 0 )+'" data-prec="'+e.precio+'"><th>'+e.codigo+'</th> <td>'+e.nombre+'</td> <td>'+e.cant+'</td> </tr>');
		}else{
			var idT = $('table#tablaProductosVenta tbody tr:last').data('id'); 
			//$('#tablaProductosVenta > tbody').append('<tr id="'+(e.id_Dom)+'" data-id="'+(idT+1)+'" data-producto="'+e.idProducto+'" data-code="'+e.codigo+'" data-desc="'+e.nombre+'" data-cant="'+e.cant+'" data-descuento="'+((e.descuento)? e.descuento : 0 )+'" data-prec="'+e.precio+'"><th>'+e.codigo+'</th> <td>'+e.nombre+'</td> <td>'+e.cant+'</td> <td>$ '+e.precio+'</td> <td>'+((e.descuento)? e.descuento : 0 )+'</td> <td>$ '+(e.total).toFixed(2)+'</td> </tr>');
			$('#tablaProductosVenta > tbody').append('<tr id="'+(e.id_Dom)+'" data-id="'+(idT+1)+'" data-producto="'+e.idProducto+'" data-code="'+e.codigo+'" data-desc="'+e.nombre+'" data-cant="'+e.cant+'" data-descuento="'+((e.descuento)? e.descuento : 0 )+'" data-prec="'+e.precio+'"><th>'+e.codigo+'</th> <td>'+e.nombre+'</td> <td>'+e.cant+'</td> </tr>');
		}
		total += e.total;
	});

	$('#tabla > tfoot').empty();
	$('#idTotalFinal').val(parseFloat(total).toFixed(2));
	$('#entrada').val(''); //resetea input
}


function verificarSiExiste(cod){
	if(codigos.length>0){
		for(var i = 0; i < codigos.length; i++){
			if(codigos[i].codigo==cod){
				return i;
			}
		}
	}
	return -1;
}

$('#btnScan').on('click', function(){
	var idSucursal = $('#idSucursal').val();
	if (idSucursal != "") {
		$('html, body').animate({
			scrollTop: $(".tablaProductosVenta").offset()
		}, 1500);
		if ( $( this ).is( ".btn-warning" ) ) {
			$(this).removeClass('btn-warning');
			$(this).addClass('btn-success');
			$('#entrada').attr('disabled',false);
			$('#entrada').focus();
			$('#txtBuscadorProductos').attr('disabled',true);
			$('#msjModo').text('Utilice el Escaner');
		} else {
			$(this).removeClass('btn-success');
			$(this).addClass('btn-warning');
			$('#entrada').attr('disabled',true);
			$('#txtBuscadorProductos').attr('disabled',false);
			$('#txtBuscadorProductos').focus();
			$('#msjModo').text('Modo Manual');
		}
	}else{
		alertify.warning('Seleccione sucursal');
	}

});
$('#entrada').focus();
$('#entrada').change(function(){
	//console.log($(this).val());
	var codigo = $(this).val();
	var sucursal = $('#idSucursal').val();
	if(verificarSiExiste(codigo)!=-1){
		var elem = codigos[verificarSiExiste(codigo)];
		codigos[verificarSiExiste(codigo)].cant+=1;
		codigos[verificarSiExiste(codigo)].total = codigos[verificarSiExiste(codigo)].cant * codigos[verificarSiExiste(codigo)].precio;
		if(codigos[verificarSiExiste(codigo).descuento>0]){
			var d = (elem.precio*elem.descuento/100)*elem.cant;
			elem.total -= d;
		}

	}else{
		$.ajax({
			url: 'apiGetProdByCodeAndSucursal.php',
			method: 'GET',
			data: 'id='+codigo+'&s='+sucursal,
			async: false,
			success: function(data){
				if(data.length>0){
					sucursal: -1
					var obj = data[0];
					var idTr = ($('table#tablaProductosVenta tbody tr:last').data('id')>0)? ($('table#tablaProductosVenta tbody tr:last').data('id') + 1) : 0; 
					codigos.push({
						dataId: idTr,
						id_Dom: 'id'+idTr,
						codigo: codigo, 
						idProducto: obj.p_id, 
						cant: 1, 
						nombre: obj.p_name, 
						precio: parseFloat(obj.p_current_price), 
						id_sucursal:  $('#idSucursal').val()
					});		

				}else{
					console.log('Producto Inexistente');
				}
			},
			error: function(err){
				console.log(err);
			}
		});
		
	}

	//console.log(codigos);
	

	cargarCodigosEnTabla();
	
	
});
//elimina  Todos los detalles
$('#btnEliminarLista').on('click', function(){
	codigos = [];
	$('#tablaProductosVenta tbody').empty();
	$('#idTotalFinal').val(0);
});

//====================SCAN===============================


	//maneja valores del contador de productos

	$('.btnAdd').click(function(){
		$este = $(this);
		$padre = $este.parent();
	    	//$label = $padre.find('.lblTotal');
	    	$label = $padre.find('.canTotal');
	    	var valor_actual = parseInt($label.val());
	    	var nuevo_valor = valor_actual + 1;
	    	$label.val(nuevo_valor);
	    	
	    });
	$('.btnLess').click(function(){
		$este = $(this);
		$padre = $este.parent();
	    	//$label = $padre.find('.lblTotal');
	    	$label = $padre.find('.canTotal');
	    	var valor_actual = parseInt($label.val());
	    	var nuevo_valor = (valor_actual <= 0)? 0 : valor_actual - 1; //limitamos a cero
	    	$label.val(nuevo_valor);
	    	
	    });



	$('.btnAdd1').click(function(){
		$este = $(this);
		$padre = $este.parent();
	    	//$label = $padre.find('.lblTotal');
	    	$label = $padre.find('#cantProductModificado');
	    	var valor_actual = parseInt($label.val());
	    	var nuevo_valor = valor_actual + 1;
	    	$label.val(nuevo_valor);
	    	var ca=parseFloat($('#ca').val());
	    	ca+= (parseFloat($('#precIdModificado').val()));
	    	$('#ca').val(ca);
	    	
	    	
	    });
	$('.btnLess1').click(function(){
		$este = $(this);
		$padre = $este.parent();
	    	//$label = $padre.find('.lblTotal');
	    	$label = $padre.find('#cantProductModificado');
	    	var valor_actual = parseInt($label.val());
	    	var nuevo_valor = (valor_actual <= 0)? 0 : valor_actual - 1; //limitamos a cero
	    	$label.val(nuevo_valor);
	    	var ca=parseFloat($('#ca').val());
	    	ca-= (parseFloat($('#precIdModificado').val()));
	    	$('#ca').val(ca.toFixed(2));
	    	
	    });

	var cargarTablaBuscar = function(q){
		var tabla = $('#tablaCliente > tbody');
		$.ajax({
			url: 'apiSearchClientes.php',
			data: [{name: 'q', value: q}],
			method: 'POST',
			success: function(data){
				tabla.empty();
				if(data.length > 0){
					$("#resultados").show();
					//$('#noUserMsj').hide();
					$('#tablaCliente > thead').show();

					for(var i = 0; i < data.length; i++){
						var o = data[i];
						var iva;
						switch(o.c_id_cond_iva){
							case '1':
							iva= "Responsable Inscripto";
							break;
							case '2':
							iva = "Exento";
							break;
							case '3':
							iva= "Monotributista";
							break;
							case '4':
							iva = "No Responsable";
							break;
							case '5':
							iva = "Consumidor Final";
							break;
							default:
							iva= "Otros";
						}
						var linea = '<tr data-id="'+o.c_id+'"><th>'+(i+1)+'</th><td>'+o.c_apellido+'</td><td>'+o.c_nombre+'</td><td>'+o.c_cuit+'</td><td>'+iva+'</td></tr>';
						tabla.append(linea);
						
					}
				}else{
					$("#results").text("No se encontraron resultados");
					tabla.empty();
					$('#resultados').hide();
					//$('#noUserMsj').show();
				}
			},
			error: function(error){
				alertify.error("Error al cargar tabla");
				console.log(error.statusText);
			}
		});
	}
	//txtBuscadorProductos
	var cargarTablaProductosVenta = function(q){
		var tabla = $('#resultadosProductos');
		var idSucursal = $("#idSucursal").val();
		if (idSucursal != "") {
			$.ajax({
				url: 'apiSearchProductos.php',
				data: [{name: 's', value: idSucursal},{name: 'q', value: q}],
				method: 'POST',
				success: function(data){
					tabla.empty();
					if(data.length > 0){
						$("#resultadosProductos").show();


						for(var i = 0; i < data.length; i++){
							var o = data[i];
							var iva;
							switch(o.c_id_cond_iva){
								case '1':
								iva= "Responsable Inscripto";
								break;
								case '2':
								iva = "Exento";
								break;
								case '3':
								iva= "Monotributista";
								break;
								case '4':
								iva = "No Responsable";
								break;
								case '5':
								iva = "Consumidor Final";
								break;
								default:
								iva= "Otros";
							}
							var linea = '<li data-id="'+o.p_id+'">'+o.p_code+' '+o.p_name+'</li>';
							tabla.append(linea);
						}
					}else{
						$("#resultsProd").text("No se encontraron resultados");
						tabla.empty();
						$('#resultadosProductos').hide();
						//$('#noUserMsj').show();
					}
				},
				error: function(error){
					alertify.error("Error al cargar lista");
					console.log(error.statusText);
				}
			});
		}else{
			alertify.warning("Primero seleccione una sucursal");
		}
	}
	//busqueda de productos para seleccionar
	$('#txtBuscadorProductos').keyup(function(){
		$este = $(this);
		//console.log($este.val());
		if($este.val().length > 2){
			//console.log("buscar q: " + $este.val());
			cargarTablaProductosVenta($este.val());
		}else{
			$('#resultsProd').empty();
			$('#resultadosProductos').empty();
			$('#resultadosProductos').hide();
		}
	});

	//llama al modal de producto por pedido
	$("#btnPedido").on('click', function(){
		if ($("#idSucursal").val()=="") {
			alertify.warning("Selecione Sucursal!");
		}else{
			$("#cantProductPedido").val("1");
			$("#productoManualModal").modal('show');
		}
		
	});

	//llama al modal de promo
	$("#btnPromo").on('click', function(){
		if ($("#idSucursal").val()=="") {
			alertify.warning("Selecione Sucursal!");
		}else{
			$("#cantPromo").val("1");
			$("#promoModal").modal('show');
		}
		
	});

	//controla los datos ingresados en el modal de producto por pedido

	$("#btnAgregarDetalleProducto").on('click', function(){
			//{"codigo":"HD1557784652","idProducto":"83","cant":1,"nombre":"Galleta","precio":110.5,"total":110.5},

			if ($("#descrProductPedido").val() == "" || $("#cantProductPedido").val() == "") {
				alertify.warning("Complete todos los campos!");
			}else{
				var arr= $("#formProductoPedido").serializeArray();
				var tot = parseFloat(arr[2].value) * parseFloat(arr[3].value);
				//ultimo id de la tabla

				if ($("#tablaProductosVenta tbody tr").length == 0) {
					var linea = '<tr id ="id0" data-id ="0" data-producto="HD0001" data-code="HD0001" data-desc="'+arr[1].value+'" data-cant="'+arr[2].value+'" data.descuento="0" data-prec="'+arr[3].value+'"><th>"HD0001"</th><td>'+arr[1].value+'</td><td>'+arr[2].value+'</td><td>'+arr[3].value+'</td><td></td></tr>';
					var id_Dom = 'id0';
					var dataID = 0;
				}else{
					var idT = $('table#tablaProductosVenta tbody tr:last').data('id'); 
					var linea = '<tr id ="id'+(idT+1)+'" data-id ="'+(idT+1)+'" data-producto="HD0001" data-code="HD0001" data-desc="'+arr[1].value+'" data-cant="'+arr[2].value+'" data.descuento="0" data-prec="'+arr[3].value+'"><th>'+arr[0].value+'</th><td>'+arr[1].value+'</td><td>'+arr[2].value+'</td></tr>';
					var id_Dom = 'id'+(idT+1);
					var dataID = (idT+1);
				}
				
				var c = {
					dataId: dataID,
					id_Dom: id_Dom,
					codigo :"HD0001",
					idProducto: -1,
					descuento: 0,
					cant: parseInt(arr[2].value),
					nombre: arr[1].value,
					precio: parseFloat(arr[3].value),
					total: tot,
					sucursal: -1,
					id_sucursal: $('#idSucursal').val()
				};

				codigos.push(c);


				var tabla = $('#tablaProductosVenta > tbody');
				tabla.append(linea);
				var cta;
				cta= parseFloat($('#idTotalFinal').val());
				cta += parseFloat(tot);
				$('#idTotalFinal').val(cta.toFixed(2));
				$('#formProductoPedido input:visible').each(function(i, e){$(e).val('');});
				$("#productoManualModal").modal('hide');
				$('#btnVenta').attr('disabled',false);
				$('#btnPresupuesto').attr('disabled',false);
			}



		});

	//controla los datos ingresados en el modal de producto por pedido

	$('#btnAgregarDetallePromo').on('click', function(){
			//{"codigo":"HD1557784652","idProducto":"83","cant":1,"nombre":"Galleta","precio":110.5,"total":110.5},

			if ($('#descrPromo').val() == '' || $('#cantPromo').val() == '' || $('#precioPromo').val() == '') {
				alertify.warning("Complete todos los campos!");
			}else{
				var arr= $("#formPromo").serializeArray();
				var tot = parseFloat(arr[2].value) * parseFloat(arr[3].value * -1);
				//ultimo id de la tabla

				if ($("#tablaProductosVenta tbody tr").length == 0) {
					var linea = '<tr id ="id0" data-id ="0" data-producto="HD0002" data-code="HD0002" data-desc="'+arr[1].value+'" data-cant="'+arr[2].value+'" data.descuento="0" data-prec="'+arr[3].value+'"><th>HD0002</th><td>'+arr[1].value+'</td><td>'+arr[2].value+'</td><td>$ '+arr[3].value+'</td><td>0</td><td>$ '+tot.toFixed(2)+'</td></tr>';
					var id_Dom = 'id0';
					var dataID = 0;
				}else{
					var idT = $('table#tablaProductosVenta tbody tr:last').data('id'); 
					var linea = '<tr id ="id'+(idT+1)+'" data-id ="'+(idT+1)+'" data-producto="HD0002" data-code="HD0002" data-desc="'+arr[1].value+'" data-cant="'+arr[2].value+'" data.descuento="0" data-prec="'+arr[3].value+'"><th>'+arr[0].value+'</th><td>'+arr[1].value+'</td><td>'+arr[2].value+'</td><td>$ '+arr[3].value+'</td><td>0</td><td>$ '+tot.toFixed(2)+'</td></tr>';
					var id_Dom = 'id'+(idT+1);
					var dataID = (idT+1);
				}
				
				//aqui
				var c = {
					dataId: dataID,
					id_Dom: id_Dom,
					codigo :"HD0002",
					idProducto: -1,
					descuento: 0,
					cant: parseInt(arr[2].value),
					nombre: arr[1].value,
					precio: parseFloat(arr[3].value),
					total: tot,
					id_sucursal: $('#idSucursal').val()
				};

				codigos.push(c);


				var tabla = $('#tablaProductosVenta > tbody');
				tabla.append(linea);
				var cta;
				cta= parseFloat($('#idTotalFinal').val());
				cta += parseFloat(tot);
				$('#idTotalFinal').val(cta.toFixed(2));
				$('#formProductoPedido input:visible').each(function(i, e){$(e).val('');});
				$("#promoModal").modal('hide');
				$('#btnVenta').attr('disabled',false);
				$('#btnPresupuesto').attr('disabled',false);
			}



		});

	

	var cargarTablaProductos = function(q,t,d){
		d = isNaN(d)? 0 : d;
		var tabla = $('#tablaProductosVenta > tbody');
		var desc= parseInt(d);
		desc/=100;
		$.ajax({
			url: 'api1Producto.php',
			data: [{name: 'q', value: q}],
			method: 'POST',
			success: function(data){
				
				for(var i = 0; i < data.length; i++){
					var o = data[i];
					var sub = parseFloat(o.p_current_price)*t;
					if (parseInt(d)>0) {
						sub -= (sub*desc);
						if ($("#tablaProductosVenta tbody tr").length == 0) {
							//var linea = '<tr id ="id0" data-id="0" data-producto="'+o.p_id+'" data-code="'+o.p_code+'" data-desc="'+o.p_name+'" data-cant="'+t+'" data-descuento="'+d+'" data-prec="'+o.p_current_price+'"><th>'+o.p_code+'</th><td>'+o.p_name+'</td><td>'+t+'</td><td>'+o.p_current_price+'</td><td>'+d+'</td><td>'+sub.toFixed(2)+'</td></tr>';
							var linea = '<tr id ="id0" data-id="0" data-producto="'+o.p_id+'" data-code="'+o.p_code+'" data-desc="'+o.p_name+'" data-cant="'+t+'" data-descuento="'+d+'" data-prec="'+o.p_current_price+'"><th>'+o.p_code+'</th><td>'+o.p_name+'</td><td>'+t+'</td></tr>';
							var id_Dom = 'id0';
							var dataID = 0;
						}else{
							var idT = $('table#tablaProductosVenta tbody tr:last').data('id'); 
							//var linea = '<tr id="id'+(idT+1)+'" data-id="'+(idT+1)+'" data-producto="'+o.p_id+'" data-code="'+o.p_code+'" data-desc="'+o.p_name+'" data-cant="'+t+'" data-descuento="'+d+'" data-prec="'+o.p_current_price+'"><th>'+o.p_code+'</th><td>'+o.p_name+'</td><td>'+t+'</td><td>'+o.p_current_price+'</td><td>'+d+'</td><td>'+sub.toFixed(2)+'</td></tr>';
							var linea = '<tr id="id'+(idT+1)+'" data-id="'+(idT+1)+'" data-producto="'+o.p_id+'" data-code="'+o.p_code+'" data-desc="'+o.p_name+'" data-cant="'+t+'" data-descuento="'+d+'" data-prec="'+o.p_current_price+'"><th>'+o.p_code+'</th><td>'+o.p_name+'</td><td>'+t+'</td></tr>';
							var id_Dom = 'id'+(idT+1);
							var dataID = idT+1;
						}

					}else{

						if ($("#tablaProductosVenta tbody tr").length == 0) {
							//var linea = '<tr id ="id0" data-id="0" data-producto="'+o.p_id+'" data-code="'+o.p_code+'" data-desc="'+o.p_name+'" data-cant="'+t+'" data-descuento="0" data-prec="'+o.p_current_price+'"><th>'+o.p_code+'</th><td>'+o.p_name+'</td><td>'+t+'</td><td>'+o.p_current_price+'</td><td>0</td><td>'+sub.toFixed(2)+'</td></tr>';
							var linea = '<tr id ="id0" data-id="0" data-producto="'+o.p_id+'" data-code="'+o.p_code+'" data-desc="'+o.p_name+'" data-cant="'+t+'" data-descuento="0" data-prec="'+o.p_current_price+'"><th>'+o.p_code+'</th><td>'+o.p_name+'</td><td>'+t+'</td></tr>';
							var id_Dom = 'id0';
							var dataID = 0;
						}else{
							var idT = $('table#tablaProductosVenta tbody tr:last').data('id'); 
							//var linea = '<tr id="id'+(idT+1)+'" data-id="'+(idT+1)+'" data-producto="'+o.p_id+'" data-code="'+o.p_code+'" data-desc="'+o.p_name+'" data-cant="'+t+'" data-descuento="0" data-prec="'+o.p_current_price+'"><th>'+o.p_code+'</th><td>'+o.p_name+'</td><td>'+t+'</td><td>'+o.p_current_price+'</td><td>0</td><td>'+sub.toFixed(2)+'</td></tr>';
							var linea = '<tr id="id'+(idT+1)+'" data-id="'+(idT+1)+'" data-producto="'+o.p_id+'" data-code="'+o.p_code+'" data-desc="'+o.p_name+'" data-cant="'+t+'" data-descuento="0" data-prec="'+o.p_current_price+'"><th>'+o.p_code+'</th><td>'+o.p_name+'</td><td>'+t+'</td></tr>';
							var id_Dom = 'id'+(idT+1);
							var dataID = idT+1;
						}
					}

					var c = {
						dataId: dataID,
						id_Dom: id_Dom,
						codigo : o.p_code,
						idProducto: o.p_id,
						descuento: d,
						cant: parseInt(t),
						nombre: o.p_name,
						precio: parseFloat(o.p_current_price),
						total: parseFloat(sub),
						id_sucursal: $('#idSucursal').val()
					};

						/*if(verificarSiExiste(c)!=-1){
							codigos[verificarSiExiste(c)].cant+=c.cant;
							codigos[verificarSiExiste(c)].total = codigos[verificarSiExiste(c)].cant * codigos[verificarSiExiste(c)].precio;
						}*/

						codigos.push(c);

						
						tabla.append(linea);
						var cta;
						cta= parseFloat($('#idTotalFinal').val());
						cta += parseFloat(o.p_current_price)*t;
						$('#idTotalFinal').val(cta.toFixed(2));
						
						cargarCodigosEnTabla();
					}
				},
				error: function(error){
					alertify.error("Error al cargar tabla");
					console.log(error.statusText);
				}
			});
	}

	//resultadosProductos
	$("#resultadosProductos").on('click', 'li', function(){
		$('#txtBuscadorProductos').val("");
		$('#resultsProd').empty();
		$('#resultadosProductos').empty();
		var id = $(this).data('id');
		//cargarTablaProductos(id);
		$('#cantidadModal').modal('show');
		$('#prodId').val(id);

	});

//agrego los detalles
$("#btnAgregarDetalle").on('click', function(){
	$('#cantidadModal').modal('hide');
	var descuento= parseInt($('#descuento').val());
	$('#descuento').val('0');
	var id= $('#prodId').val();
	var cant= $('.canTotal').val();
		//cargarTablaProductos(id,cant);
		cargarTablaProductos(id,cant,descuento);
		$('.canTotal').val("1");
		//calculo producto
		$('#btnVenta').attr('disabled',false);
		$('#btnPresupuesto').attr('disabled',false);

	});


	//Buscador de clientes
	$('#txtBuscador').keyup(function(){
		$este = $(this);
		//console.log($este.val());
		if($este.val().length > 2){
			//console.log("buscar q: " + $este.val());
			cargarTablaBuscar($este.val());
		}else{
			$('#results').empty();
			$('#tablaCliente > tbody').empty();
			$('#resultados').hide();
		}
	});

	//carga el membrete del cliente
	var cargarMembreteCliente = function(q){
		//var tabla = $('#tablaCliente > tbody');
		$.ajax({
			url: 'api1Cliente.php',
			data: [{name: 'q', value: q}],
			method: 'POST',
			success: function(data){
				
				if(data.length > 0){
					$("#membrete").show();
					//$('#noUserMsj').hide();
					for(var i = 0; i < data.length; i++){
						var o = data[i];
						var iva;
						switch(o.c_id_cond_iva){
							case '1':
							iva= "Responsable Inscripto";
							break;
							case '2':
							iva = "Exento";
							break;
							case '3':
							iva= "Monotributista";
							break;
							case '4':
							iva = "No Responsable";
							break;
							case '5':
							iva = "Consumidor Final";
							break;
							default:
							iva= "Otros";
						}

						$("#apellido").text("Apellido: "+o.c_apellido);
						$("#nombre").text("Nombre: "+o.c_nombre);
						$("#cuit").text("Cuit: "+o.c_cuit);
						$("#iva").text("IVA: "+iva);
						$("#tel").text("Tel: "+o.c_cel);
						$("#email").text("Email: "+o.c_email);
						$("#localidad").text("Localidad: "+o.c_id_localidad);

					}
				}else{
					$("#apellido").text("Apellido: ");
					$("#nombre").text("Nombre: ");
					$("#cuit").text("Cuit: ");
					$("#iva").text("IVA: ");
					$("#tel").text("Tel: ");
					$("#email").text("Email: ");
					$("#localidad").text("Localidad: ");
				}
			},
			error: function(error){
				alertify.error("Error al cargar tabla");
				console.log(error.statusText);
			}
		});
	}
	$("#tablaCliente > tbody").on('click', 'tr', function(){
		$('#txtBuscador').val("");
		$('#results').empty();
		$('#tablaCliente > tbody').empty();
		$('#resultados').hide();
		$('#buscador').hide();
		$('#membrete').show();
		var id = $(this).data('id');
		cargarMembreteCliente(id);
		$('#idCliente').val(id);
	});

	//paline:

	$('#btnGuardar').click(function(e){
		e.preventDefault();
		if($('#fechaLimite_sel').val()==''){
			alertify.error('Elija una fecha de devolución', 2, function(){
				$('#fechaLimite_sel').focus();
			});

		}else{
			$.ajax({
				url: 'apiComodato.php',
				data: [
						{name: 'acc', value: 'nuevo'}, 
						{name: 'productos', value: JSON.stringify(codigos)},
						{name: 'id_cliente', value: $('#idCliente').val()},
						{name: 'fecha_limite', value: $('#fechaLimite').val()},
						{name: 'id_sucursal', value: $('#idSucursal').val()},
						{name: 'observaciones', value: $('#txtObservaciones').val()}
					  ],
				method: 'POST',
				success: function(data){
					console.log(data);
					if(data.success){
						alertify.success('Comodato generado correctamente!', 2, function(){
							document.location = 'comodato.php';
						});
					}else{
						alertify.error('Ocurrió un error al guardar.', 2);
					}
				},
				error: function(err){
					alertify.error('Ocurrió un error al guardar.', 2);
				}
			});
		}
			
	});

</script>