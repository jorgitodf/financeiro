
<div class="container-fluid col-sm-12 col-md-12 col-lg-12">
	<div class="row-fluid">
		<?php if (!empty($message)):?>
			<section class="col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-sm-6 col-sm-offset-3 jumbotron" id="sec_extrato">

				<div class="alert alert-danger text-center" role="alert" id="div_sem_extrato">
					<h3><?php echo !empty($message) ? $message : ""; ?></h3>
				</div>
				<div class="" id="div_sem_extrato">
					<a class="btn btn-primary" id="btn_sair_sem_extrato" href="/conta/acessar/<?php echo $idConta; ?>">Sair</a>
				</div>
			</section>
		<?php else:?>
			<section class="col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2 col-sm-8 col-sm-offset-2 jumbotron" id="sec_com_extrato">
				<div class="panel panel-primary" id="table_extrato">
					<div class="panel-heading" id="">
						<h3 class="panel-title">Extrato período: <?php echo!empty($data_inicial) ? date("d/m/Y", strtotime($data_inicial)) : ''; ?> a <?php echo!empty($data_final) ? date("d/m/Y", strtotime($data_final)) : ''; ?></h3>
					</div>
					<div class="panel-body" id="panel_body_extrato">
						<table class="table table-hover" id="table_extrato_atual">
							<thead>
							<tr>
								<th class="data_mov_cab" width="22%">Data de Movimentação</th>
								<th width="35%">Movimentação</th>
								<th width="25%">Categoria</th>
								<th class="valor_mov_cab" width="15%">Valor</th>
								<th class="saldo_mov_cab">Saldo</th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ($extrato as $linha): ?>
								<tr>
									<td class="td_extrato_deb_data"><?php echo date("d/m/Y", strtotime($linha['data_movimentacao'])); ?></td>
									<?php if ($linha['op'] == 'Crédito'): ?>
										<td class="td_extrato_cre"><?php echo ucwords(strtolower(mb_convert_case($linha['mov'], MB_CASE_TITLE))); ?></td>
										<td class="td_extrato_cre"><?php echo ucwords(strtolower(mb_convert_case($linha['cat'], MB_CASE_TITLE))); ?></td>
										<td align="center" class="td_extrato_cre"><?php echo number_format($linha['val'], 2, ',', '.'); ?></td>
										<td align="center" class="td_extrato_cre"><?php echo number_format($linha['sal'], 2, ',', '.'); ?></td>
									<?php else: ?>
										<?php if ($linha['dp'] == 'S'): ?>
											<td class="td_extrato_deb_fixa"><?php echo utf8_decode(ucwords(strtolower(mb_convert_case($linha['mov'], MB_CASE_TITLE)))); ?></td>
										<?php else: ?>
											<td><?php echo ucwords(strtolower(mb_convert_case($linha['mov'], MB_CASE_TITLE))); ?></td>
										<?php endif; ?>
										<td><?php echo $linha['cat']; ?></td>
										<td align="center" class="td_extrato_deb"><?php echo number_format($linha['val'], 2, ',', '.'); ?></td>
										<td align="center" class="td_extrato_deb_saldo"><?php echo number_format($linha['sal'], 2, ',', '.'); ?></td>
									<?php endif; ?>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
						<div class="form-group form-group-sm col-sm-12 col-md-12">
							<a class="btn btn-primary btn-sm" id="btn_sair_extrato" href="/conta/acessar/<?php echo $idConta; ?>">Sair</a>
						</div>
					</div>
				</div>
			</section>
		<?php endif; ?>
	</div>
</div>
