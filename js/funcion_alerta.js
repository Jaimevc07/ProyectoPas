function call_alert(employeeId) {
  swal({
    title: "¿Está seguro?",
    text: "¡Esta acción no se puede deshacer!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          url: "employee_delete.php?id=" + employeeId,
          type: "GET",
          data: FormData,
          async: false,
          contentType: false,
          processData: false,
          success: function (data) {
            swal("Empleado eliminado satisfactoriamente!", {
              icon: "success",
              showCancelButton: false,
              closeOnClickOutside: false,
              closeOnEsc: false
            }).then((yes) => {
              if (yes) {
                location.reload();
              }
            });
          },
          error: function (data) {
            swal("Ha ocurrido un error durante la eliminación!", {
              icon: "error",
            });
          }
        })

      }
    });
}

function alerta_existente(){
  var canProceed = true;
  var formData = new FormData($("#formulario")[0]);

  for (var pair of formData.entries()){
    if (pair[1] === ''){
      canProceed = false;
      break;
    }
  }

  if (!canProceed){
    return;
  }

  $.ajax({
    url: "employee_save.php",
    type: "POST",
    data: formData,
    async: false,
    contentType: false,
    processData: false,
    success: function (data) {
      swal("Empleado agregado satisfactoriamente!", {
        icon: "success",
        showCancelButton: false,
        closeOnClickOutside: false,
        closeOnEsc: false
      }).then((yes) => {
        if (yes) {
          document.location.href = "employees.php";
        }
      });
    },
    error: function (data) {
      swal("¡El empleado ya existe en la base de datos!", {
        icon: "error",
      });
    }
  })
}

function alerta_cant_delete(){
    swal("¡El RFI usado es distinto al del usuario que intenta borrar!", {
        icon: "error",
      });
}