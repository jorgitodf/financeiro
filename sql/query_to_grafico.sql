SET @idCategoria := 36;

SELECT cat.nome_categoria AS categoria, tb1.total AS 'Janeiro', tb2.total AS 'Fevereiro', tb3.total AS 'Março', tb4.total AS 'Abril', tb5.total AS 'Maio', tb6.total AS 'Junho', tb7.total AS 'Julho', tb8.total AS 'Agosto', tb9.total AS 'Setembro', tb10.total AS 'Outubro', tb11.total AS 'Novembro', tb12.total AS 'Dezembro', fncCalculaValorTotal(@idCategoria, 2017) AS Total
FROM tb_categoria cat
JOIN (
	SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, @idCategoria) AS id_categoria
	FROM tb_extrato e
	WHERE e.fk_id_categoria = @idCategoria
	AND data_movimentacao BETWEEN '2017-01-01' AND '2017-01-31'
	AND e.tipo_operacao = 'Débito') AS tb1 ON (tb1.id_categoria = cat.id_categoria)
JOIN (
	SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, @idCategoria) AS id_categoria
	FROM tb_extrato e
	WHERE e.fk_id_categoria = @idCategoria
	AND data_movimentacao BETWEEN '2017-02-01' AND '2017-02-29'
	AND e.tipo_operacao = 'Débito') AS tb2 ON (tb2.id_categoria = cat.id_categoria)
JOIN (
	SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, @idCategoria) AS id_categoria
	FROM tb_extrato e
	WHERE e.fk_id_categoria = @idCategoria
	AND data_movimentacao BETWEEN '2017-03-01' AND '2017-03-31'
	AND e.tipo_operacao = 'Débito') AS tb3 ON (tb3.id_categoria = cat.id_categoria)
JOIN (
	SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, @idCategoria) AS id_categoria
	FROM tb_extrato e

	WHERE e.fk_id_categoria = @idCategoria
	AND data_movimentacao BETWEEN '2017-04-01' AND '2017-04-30'
	AND e.tipo_operacao = 'Débito') AS tb4 ON (tb4.id_categoria = cat.id_categoria)
JOIN (
	SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, @idCategoria) AS id_categoria
	FROM tb_extrato e
	WHERE e.fk_id_categoria = @idCategoria
	AND data_movimentacao BETWEEN '2017-05-01' AND '2017-05-31'
	AND e.tipo_operacao = 'Débito') AS tb5 ON (tb5.id_categoria = cat.id_categoria)
JOIN (
	SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, @idCategoria) AS id_categoria
	FROM tb_extrato e
	WHERE e.fk_id_categoria = @idCategoria
	AND data_movimentacao BETWEEN '2017-06-01' AND '2017-06-30'
	AND e.tipo_operacao = 'Débito') AS tb6 ON (tb6.id_categoria = cat.id_categoria)
JOIN (
	SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, @idCategoria) AS id_categoria
	FROM tb_extrato e

	WHERE e.fk_id_categoria = @idCategoria
	AND data_movimentacao BETWEEN '2017-07-01' AND '2017-07-31'
	AND e.tipo_operacao = 'Débito') AS tb7 ON (tb7.id_categoria = cat.id_categoria)
JOIN (
	SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, @idCategoria) AS id_categoria
	FROM tb_extrato e
	WHERE e.fk_id_categoria = @idCategoria
	AND data_movimentacao BETWEEN '2017-08-01' AND '2017-08-31'
	AND e.tipo_operacao = 'Débito') AS tb8 ON (tb8.id_categoria = cat.id_categoria)
JOIN (
	SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, @idCategoria) AS id_categoria
	FROM tb_extrato e
	WHERE e.fk_id_categoria = @idCategoria
	AND data_movimentacao BETWEEN '2017-09-01' AND '2017-09-30'
	AND e.tipo_operacao = 'Débito') AS tb9 ON (tb9.id_categoria = cat.id_categoria)
JOIN (
	SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, @idCategoria) AS id_categoria
	FROM tb_extrato e
	WHERE e.fk_id_categoria = @idCategoria
	AND data_movimentacao BETWEEN '2017-10-01' AND '2017-10-31'
	AND e.tipo_operacao = 'Débito') AS tb10 ON (tb10.id_categoria = cat.id_categoria)
JOIN (
	SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, @idCategoria) AS id_categoria
	FROM tb_extrato e
	WHERE e.fk_id_categoria = @idCategoria
	AND data_movimentacao BETWEEN '2017-11-01' AND '2017-11-30'
	AND e.tipo_operacao = 'Débito') AS tb11 ON (tb11.id_categoria = cat.id_categoria)
JOIN (
	SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, @idCategoria) AS id_categoria
	FROM tb_extrato e
	WHERE e.fk_id_categoria = @idCategoria
	AND data_movimentacao BETWEEN '2017-12-01' AND '2017-12-31'
	AND e.tipo_operacao = 'Débito') AS tb12 ON (tb12.id_categoria = cat.id_categoria);