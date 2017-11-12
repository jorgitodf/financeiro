
      <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      
      <script src="<?php echo base_url('assets/js/jquery.maskMoney.js') ?>"></script>
      <script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
      <script src="<?php echo base_url('assets/js/scripts.js') ?>"></script>
      <script src="<?php echo base_url('assets/js/ajax.js') ?>"></script>
      <script src="<?php echo base_url('assets/js/jquery.maskedinput.js') ?>"></script>
      <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $("#valor").maskMoney({showSymbol: true, symbol: "R$ ", decimal: ",", thousands: "."});
            $("#valor_pgto").maskMoney({showSymbol: true, symbol: "R$ ", decimal: ",", thousands: "."});
            $("#valor_compra").maskMoney({showSymbol: true, symbol: "R$ ", decimal: ",", thousands: "."});
            $("#encargos").maskMoney({showSymbol: true, symbol: "R$ ", decimal: ",", thousands: "."});
            $("#iof").maskMoney({showSymbol: true, symbol: "R$ ", decimal: ",", thousands: "."});
            $("#anuidade").maskMoney({showSymbol: true, symbol: "R$ ", decimal: ",", thousands: "."});
            $("#protecao_prem").maskMoney({showSymbol: true, symbol: "R$ ", decimal: ",", thousands: "."});
            $("#valor_pagar").maskMoney({showSymbol: true, symbol: "R$ ", decimal: ",", thousands: "."});
            $("#juros_fat").maskMoney({showSymbol: true, symbol: "R$ ", decimal: ",", thousands: "."});
            $("#restante").maskMoney({showSymbol: true, symbol: "R$ ", decimal: ",", thousands: "."});
            $("#valor_total").maskMoney({showSymbol: true, symbol: "R$ ", decimal: ",", thousands: "."});
            $("#valor_cre").maskMoney({showSymbol: true, symbol: "R$ ", decimal: ",", thousands: "."});
        });
        jQuery(function($) {
            $("#num_cartao").mask("9999.9999.9999.9999");
            $("#data_validade").mask("99/9999");
        });
      </script>
    </body>
</html>
