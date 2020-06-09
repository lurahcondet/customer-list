var customerId = 0;
var Customer = {
	get: function(){
		var self = this;
		jQuery("#customer-grid").html('');
		jQuery.ajax({
			url: 'get.php',
			success: function(response){
				if(!response){
					return;
				}

				if(response.status.code == 200){
					self.refreshGrid(response.result);
				}
			}
		});
	},

	add: function(url, data){
		var self = this;
		jQuery.ajax({
			url: url,
			data: data,
			type: "POST",
			success: function(response){
				if(!response){
					return;
				}

				if(response.status.code == 200){
					self.get();
					return;
				}
				jQuery('#alertModalCenter').modal('show');
			}
		});
	},

	delete: function(identifier){
		var self = this;
		jQuery.ajax({
			url: 'delete.php',
			data: {id: customerId},
			type: "POST",
			success: function(response){
				customerId = 0;
				jQuery('#' + identifier).modal('hide');
				if(!response){
					return;
				}

				if(response.status.code == 200){
					self.get();
					return;
				}
				jQuery('#alertModalCenter').modal('show');

			},
			error: function(){
				customerId = 0;
				jQuery('#' + identifier).modal('hide');
				jQuery('#alertModalCenter').modal('show');
			}
		});		
	},

	edit: function(id){
		this.pick(id);
		jQuery.ajax({
			url: 'get.php',
			data: {id:id},
			type: "POST",
			success: function(response){
				if(!response){
					return;
				}

				if(response.status.code == 200){
					jQuery.each(response.result, function(i, rowData){
						jQuery("#id").val(rowData.id);
						jQuery("#name").val(rowData.name);
						jQuery("#email").val(rowData.email);
						jQuery("#gender").val(rowData.gender);
						jQuery("#married").val(rowData.married);
						jQuery("#address").val(rowData.address);
					});

				}
			},
			error: function(){
				customerId = 0;
				jQuery('#alertModalCenter').modal('show');
			}
		});

	},

	pick: function(id){
		customerId = id;
	},

	refreshGrid: function(data){
		jQuery.each(data, function(i, rowData){
			var row = '<div class="row">' +
			'<div class="col-md-2">' + rowData.name + '</div>' +
			'<div class="col-md-2">' + rowData.email + '</div>' +
			'<div class="col-md-2">' + rowData.password + '</div>' +
			'<div class="col-md-1">' + rowData.gender + '</div>' +
			'<div class="col-md-1">' + rowData.married + '</div>' +
			'<div class="col-md-3">' + rowData.address + '</div>' +
			'<div class="col-md-1"><a href="#" onclick="customer.edit(' + rowData.id + ')" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;&nbsp;' +
			'<a href="#" onclick="customer.pick(' + rowData.id + ')" title="Delete" data-toggle="modal" data-target="#confModalCenter"><i class="fa fa-trash-o"></i></a></div>' +
		'</div>';
			jQuery("#customer-grid").append(row);
		});
	}
};