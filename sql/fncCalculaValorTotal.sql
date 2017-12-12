DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `fncCalculaValorTotal`(idCategoria INT, ano INT) RETURNS decimal(8,2)
BEGIN
	DECLARE total DECIMAL (8,2);
    
    SET total := (SELECT SUM(valor) AS total FROM tb_extrato e JOIN tb_categoria c ON (c.id_categoria = e.fk_id_categoria)
WHERE c.id_categoria = idCategoria AND data_movimentacao BETWEEN CONCAT(ano,'-01-01') AND CONCAT(ano,'-12-31') AND e.tipo_operacao = 'Débito');

RETURN total;
END$$
DELIMITER ;
