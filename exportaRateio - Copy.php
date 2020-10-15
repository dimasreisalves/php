<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- Meta, title, CSS, favicons, etc. -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Solução Compacta</title>

	<!-- Bootstrap -->
	<link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Font Awesome -->
	<link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<!-- NProgress -->
	<link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
	<!-- iCheck -->
	<link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
	<!-- bootstrap-wysiwyg -->
	<link href="../vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
	<!-- Switchery -->
	<link href="../vendors/switchery/dist/switchery.min.css" rel="stylesheet">
	<!-- bootstrap-daterangepicker -->
	<link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">


	<!-- Custom Theme Style -->
	<link href="../build/css/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md" onload="loadOff()">

	<div class="container body">
		<div class="main_container">
			<!-- MENU DE NAVEGAÇÃO LATERAL -->
			<?php
			include_once "MontaMenu.php";
			$menu = new MontaMenu();
			?>
			<!-- FIM MENU DE NAVEGAÇÃO LATERAL -->

			<!-- page content -->
			<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						<div class="title_left">
							<h3>Exportação de rateios</h3>
						</div>
					</div>

					<div class="clearfix"></div>
					<div class="row" style="height: 800px">
						<div class="col-md-12">
							<!-- Formulário da OS-->
							<!-- PAINEL DE CADASTRO -->
							<div class="x_panel">
								<!-- TÍTULO DO PAINEL-->  
								<div class="x_title">
									<h2>Selecione os períodos e exporte para excel.</h2>
									<br><br>
									
									<div class="clearfix"></div>
								</div>

								<!-- CONTEÚDO DO PAINEL -->
								<div class="x_content"><br/>
									<form method="POST" action="exportarRateio.php">
										<div class="form-group">
											<label class="control-label col-md-1 col-sm-3 col-xs-12">DE:</label>
											<div class="col-md-2 col-sm-3 col-xs-12">
												<input type="text" name="dataInicio" id="dataInicio" class="form-control col-md-3 col-xs-12" data-inputmask="'mask' : '99/99/9999'">
											</div>

											<label class="control-label col-md-1 col-sm-3 col-xs-12">ATÉ:</label>
											<div class="col-md-2 col-sm-3 col-xs-12">
												<input type="text" name="dataFim" id="dataFim" class="form-control col-md-7 col-xs-12" data-inputmask="'mask' : '99/99/9999'">
											</div>
											
											<button type="submit" class="btn btn-info">Exportar para excel</button>
										</div>
									</form>
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /page content -->
		</div>
	</div>
</div>
</div>

<!-- FOOTER -->
<footer>
	<div class="pull-right">
		Compacta Dashboard desenvolvida por <a href="http://www.solucaocompacta.com.br">Solução Compacta</a>
	</div>
	<div class="clearfix"></div>
</footer>
<!-- /FOOTER -->
</div>


<!-- /INSERÇÃO NA AGENDA -->

<div id="fc_create" data-toggle="modal" data-target="#CalenderModalNew"></div>

<!-- jQuery -->
<script src="../vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../vendors/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="../vendors/nprogress/nprogress.js"></script>
<!-- iCheck -->
<script src="../vendors/iCheck/icheck.min.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="../vendors/moment/min/moment.min.js"></script>
<script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap-wysiwyg -->
<script src="../vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
<script src="../vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
<script src="../vendors/google-code-prettify/src/prettify.js"></script>
<!-- jQuery Tags Input -->
<script src="../vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
<!-- Parsley -->
<script src="../vendors/parsleyjs/dist/parsley.js"></script>
<!-- Autosize -->
<script src="../vendors/autosize/dist/autosize.min.js"></script>
<!-- jquery.inputmask -->
<script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
<!-- Datatables -->
<script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="../vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
<script src="../vendors/jszip/dist/jszip.min.js"></script>
<script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
<script src="../vendors/pdfmake/build/vfs_fonts.js"></script>
<!-- jQuery autocomplete -->
<script src="../vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
<!-- starrr -->
<script src="../vendors/starrr/dist/starrr.js"></script>
<!-- Select2 -->
<script src="../vendors/select2/dist/js/select2.full.min.js"></script>

<!-- Custom Theme Scripts -->
<script src="../build/js/custom.min.js"></script>
<!-- PDF -->
<script src="jspdf/dist/jspdf.min.js"></script>
<!-- Ion.RangeSlider -->
<script src="../vendors/ion.rangeSlider/js/ion.rangeSlider.min.js"></script>


<!-- bootstrap-daterangepicker -->
<script>
	$(document).ready(function() {
		$('#dataInicio').daterangepicker({
			singleDatePicker: true,
			calender_style: "picker_4"
		}, function(start, end, label) {
			console.log(start.toISOString(), end.toISOString(), label);
		});
		$('#dataFim').daterangepicker({
			singleDatePicker: true,
			calender_style: "picker_4"
		}, function(start, end, label) {
			console.log(start.toISOString(), end.toISOString(), label);
		});
	});
</script>
<!-- /bootstrap-daterangepicker -->

<!-- bootstrap-wysiwyg -->
<script>
	$(document).ready(function() {
		function initToolbarBootstrapBindings() {
			var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier',
			'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
			'Times New Roman', 'Verdana'
			],
			fontTarget = $('[title=Font]').siblings('.dropdown-menu');
			$.each(fonts, function(idx, fontName) {
				fontTarget.append($('<li><a data-edit="fontName ' + fontName + '" style="font-family:\'' + fontName + '\'">' + fontName + '</a></li>'));
			});
			$('a[title]').tooltip({
				container: 'body'
			});
			$('.dropdown-menu input').click(function() {
				return false;
			})
			.change(function() {
				$(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');
			})
			.keydown('esc', function() {
				this.value = '';
				$(this).change();
			});

			$('[data-role=magic-overlay]').each(function() {
				var overlay = $(this),
				target = $(overlay.data('target'));
				overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
			});

			if ("onwebkitspeechchange" in document.createElement("input")) {
				var editorOffset = $('#editor').offset();

				$('.voiceBtn').css('position', 'absolute').offset({
					top: editorOffset.top,
					left: editorOffset.left + $('#editor').innerWidth() - 35
				});
			} else {
				$('.voiceBtn').hide();
			}
		}

		function showErrorAlert(reason, detail) {
			var msg = '';
			if (reason === 'unsupported-file-type') {
				msg = "Unsupported format " + detail;
			} else {
				console.log("error uploading file", reason, detail);
			}
			$('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>' +
				'<strong>File upload error</strong> ' + msg + ' </div>').prependTo('#alerts');
		}

		initToolbarBootstrapBindings();

		$('#editor').wysiwyg({
			fileUploadError: showErrorAlert
		});

		window.prettyPrint;
		prettyPrint();
	});
</script>
<!-- /bootstrap-wysiwyg -->

<!-- jquery.inputmask -->
<script>
	$(document).ready(function() {
		$(":input").inputmask();
	});
</script>
<!-- /jquery.inputmask -->

<!-- jQuery Tags Input -->
<script>
	function onAddTag(tag) {
		alert("Added a tag: " + tag);
	}

	function onRemoveTag(tag) {
		alert("Removed a tag: " + tag);
	}

	function onChangeTag(input, tag) {
		alert("Changed a tag: " + tag);
	}

	$(document).ready(function() {
		$('#tags_1').tagsInput({
			width: 'auto'
		});
	});
</script>
<!-- /jQuery Tags Input -->

<!-- Parsley -->
<script>
	$(document).ready(function() {
		$.listen('parsley:field:validate', function() {
			validateFront();
		});
		$('#demo-form .btn').on('click', function() {
			$('#demo-form').parsley().validate();
			validateFront();
		});
		var validateFront = function() {
			if (true === $('#demo-form').parsley().isValid()) {
				$('.bs-callout-info').removeClass('hidden');
				$('.bs-callout-warning').addClass('hidden');
			} else {
				$('.bs-callout-info').addClass('hidden');
				$('.bs-callout-warning').removeClass('hidden');
			}
		};
	});

	$(document).ready(function() {
		$.listen('parsley:field:validate', function() {
			validateFront();
		});
		$('#demo-form2 .btn').on('click', function() {
			$('#demo-form2').parsley().validate();
			validateFront();
		});
		var validateFront = function() {
			if (true === $('#demo-form2').parsley().isValid()) {
				$('.bs-callout-info').removeClass('hidden');
				$('.bs-callout-warning').addClass('hidden');
			} else {
				$('.bs-callout-info').addClass('hidden');
				$('.bs-callout-warning').removeClass('hidden');
			}
		};
	});
	try {
		hljs.initHighlightingOnLoad();
	} catch (err) {}
</script>
<!-- /Parsley -->

<!-- Autosize -->
<script>
	$(document).ready(function() {
		autosize($('.resizable_textarea'));
	});
</script>
<!-- /Autosize --> 

<!-- jquery.inputmask -->
<script>
	$(document).ready(function() {
		$(":input").inputmask();
	});
</script>
<!-- /jquery.inputmask -->

<!-- Datatables -->
<script>
	$(document).ready(function() {
		var handleDataTableButtons = function() {
			if ($("#datatable-buttons").length) {
				$("#datatable-buttons").DataTable({
					dom: "Bfrtip",
					buttons: [
					{
						extend: "copy",
						className: "btn-sm"
					},
					{
						extend: "csv",
						className: "btn-sm"
					},
					{
						extend: "excel",
						className: "btn-sm"
					},
					{
						extend: "pdfHtml5",
						className: "btn-sm"
					},
					{
						extend: "print",
						className: "btn-sm"
					},
					],
					responsive: true
				});
			}
		};

		TableManageButtons = function() {
			"use strict";
			return {
				init: function() {
					handleDataTableButtons();
				}
			};
		}();

		$('#datatable').dataTable();

		$('#datatable-keytable').DataTable({
			keys: true
		});

		$('#datatable-responsive').DataTable();

		$('#datatable-scroller').DataTable({
			ajax: "js/datatables/json/scroller-demo.json",
			deferRender: true,
			scrollY: 380,
			scrollCollapse: true,
			scroller: true
		});

		$('#datatable-fixed-header').DataTable({
			fixedHeader: true
		});

		var $datatable = $('#datatable-checkbox');

		$datatable.dataTable({
			'order': [[ 1, 'asc' ]],
			'columnDefs': [
			{ orderable: false, targets: [0] }
			]
		});
		$datatable.on('draw.dt', function() {
			$('input').iCheck({
				checkboxClass: 'icheckbox_flat-green'
			});
		});

		TableManageButtons.init();
	});
</script>
<!-- /Datatables -->   

<!-- jQuery autocomplete usuarios -->
<script>
	$(document).ready(function() {
		var countries = {<?php echo $usuarios ?>};
		var countriesArray = $.map(countries, function(value, key) {
			return {
				value: value,
				data: key
			};
		});

        // initialize autocomplete with custom appendTo
        $('#usuario').autocomplete({
        	lookup: countriesArray
        });
    });
</script>
<!-- /jQuery autocomplete -->

<!-- jQuery autocomplete clientes -->
<script>
	$(document).ready(function() {
		var countries = {<?php echo $clientes ?>};
		var countriesArray = $.map(countries, function(value, key) {
			return {
				value: value,
				data: key
			};
		});

        // initialize autocomplete with custom appendTo
        $('#cliente').autocomplete({
        	lookup: countriesArray
        });
    });
</script>
<!-- /jQuery autocomplete -->

<!-- Select2 -->
<script>
	$(document).ready(function() {
		$(".select2_single").select2({
			placeholder: "Defina a informação",
			allowClear: true
		});
		$(".select2_group").select2({});
		$(".select2_multiple").select2({
			maximumSelectionLength: 4,
			placeholder: "Limite de seleção é 4",
			allowClear: true
		});
	});
</script>

</body>
</html>
