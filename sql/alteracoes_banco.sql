ALTER TABLE `conta`.`tb_categoria`
ADD COLUMN `despesa_fixa` CHAR(1) NOT NULL AFTER `nome_categoria`;
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='S' WHERE `id_categoria`='1';

UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='2';

UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='3';

UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='4';

UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='S' WHERE `id_categoria`='5';

UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='S' WHERE `id_categoria`='6';

UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='7';

UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='8';

UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='9';

UPDATE tb_categoria SET despesa_fixa = 'S' WHERE id_categoria IN (5,6,11,18,19,22,25,31,37,42,48);

UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='10';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='12';

UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='13';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='14';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='15';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='16';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='20';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='21';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='23';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='24';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='26';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='27';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='28';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='29';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='30';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='32';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='33';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='34';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='35';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='36';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='38';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='39';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='40';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='41';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='43';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='44';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='45';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='46';
UPDATE `conta`.`tb_categoria` SET `despesa_fixa`='N' WHERE `id_categoria`='47';


ALTER TABLE `conta`.`tb_categoria` ADD COLUMN `tipo` CHAR(1) NOT NULL AFTER `despesa_fixa`;

		} else if ($this->input->post()) {
			$idUsuario = $this->input->post('idUsuario');
			$num_cartao = $this->input->post('num_cartao');
			$data_validade = $this->input->post('data_validade');
			$bandeira = $this->input->post('bandeira');
			$banco = $this->input->post('banco');

			$dados = ['numero_cartao'=>$num_cartao, 'data_validade'=>$data_validade, 'fk_id_bandeira_cartao'=>$bandeira,
				'fk_id_usuario'=>$idUsuario, 'fk_cod_banco'=>$banco];

			$return = $this->cartaocredito_model->cadastrarCartaoCredito($dados);
			if ($return['status'] == 'success') {
				$json = array('status'=>'success', 'message'=>$return['message']);
			}  else {
				$json = array('status'=>'error', 'message'=>$return['message']);
			}
			return $this->output->set_content_type('application/json')->set_output(json_encode(array($json)));


disabled="disabled"


ALTER TABLE `conta`.`tb_cartao_credito` ADD COLUMN `ativo` CHAR(1) NOT NULL AFTER `fk_cod_banco`;
